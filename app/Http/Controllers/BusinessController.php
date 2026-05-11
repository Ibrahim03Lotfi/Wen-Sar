<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\CategoryBusinessRanking;
use App\Models\District;
use App\Models\SubArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    private function getRankingRowsForCategory(?int $categoryId)
    {
        if (!$categoryId) {
            return collect();
        }

        $category = Category::select('id', 'parent_id')->find($categoryId);

        if (!$category) {
            return collect();
        }

        $candidateCategoryIds = [$category->id];

        // For subcategories, fall back to parent rankings only when needed.
        if ($category->parent_id) {
            $candidateCategoryIds[] = $category->parent_id;
        }

        $rows = CategoryBusinessRanking::whereIn('category_id', $candidateCategoryIds)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$category->id])
            ->orderBy('rank')
            ->get(['business_id', 'category_id', 'rank']);

        return $rows
            ->groupBy('business_id')
            ->map(fn ($businessRows) => $businessRows->first());
    }

    private function applyManagerRankings($businesses, ?int $categoryId)
    {
        $rankings = $this->getRankingRowsForCategory($categoryId);

        if ($rankings->isEmpty()) {
            return $businesses;
        }

        $ranked = collect();
        $unranked = collect();

        foreach ($businesses as $business) {
            if ($rankings->has($business->id)) {
                $business->manager_rank = $rankings[$business->id]->rank;
                $ranked->push($business);
            } else {
                $unranked->push($business);
            }
        }

        return $ranked->sortBy('manager_rank')->values()->merge($unranked->values());
    }

    public function search(Request $request)
    {
        $query = Business::approved()->with(['category', 'subArea', 'reviews']);

        if ($request->has('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->has('sub_area_id')) {
            $query->where('sub_area_id', $request->sub_area_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('query')) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }

        // Apply Ranking Algorithm (Subquery for average rating)
        $businesses = $query->get()->map(function($business) {
            $avgRating = $business->reviews->avg('rating') ?: 0;
            // Ranking Algorithm: Score = (rating * 0.5) + (views * 0.3) + (featured * 0.2)
            // Normalize views (example: views/1000)
            $business->ranking_score = ($avgRating * 0.5) + 
                                     (min($business->views_count / 100, 5) * 0.3) + 
                                     (($business->is_featured ? 5 : 0) * 0.2);
            return $business;
        })->sortByDesc('ranking_score')->values();

        // Shuffle results for search (no ranking system)
        $businesses = $businesses->shuffle()->values();

        $categories = Category::all();
        $districts = District::all();

        return view('businesses.index', compact('businesses', 'categories', 'districts'));
    }

    public function apiSearch(Request $request)
    {
        $query = Business::approved()->with(['category', 'subArea', 'reviews']);

        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->has('sub_area_id') && $request->sub_area_id) {
            $query->where('sub_area_id', $request->sub_area_id);
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('query') && $request->query) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }

        // Apply Ranking Algorithm
        $businesses = $query->get()->map(function($business) {
            $avgRating = $business->reviews->avg('rating') ?: 0;
            $business->ranking_score = ($avgRating * 0.5) + 
                                     (min($business->views_count / 100, 5) * 0.3) + 
                                     (($business->is_featured ? 5 : 0) * 0.2);
            return $business;
        })->sortByDesc('ranking_score')->values();

        // Shuffle results for search (no ranking system)
        $businesses = $businesses->shuffle()->values();

        $categories = Category::all();
        $districts = District::all();

        // Return partial view for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('businesses._results', compact('businesses', 'categories', 'districts'))->render(),
                'count' => $businesses->count()
            ]);
        }

        return view('businesses.index', compact('businesses', 'categories', 'districts'));
    }

    public function show(Business $business)
    {
        // Only show approved businesses to public, unless user is the owner or admin
        if ($business->status !== 'approved') {
            if (!auth()->check() || (auth()->id() !== $business->owner_id && !auth()->user()->hasRole('admin'))) {
                abort(404);
            }
        }

        $business->increment('views_count');
        $business->load(['category', 'subArea', 'reviews' => function($query) {
            $query->latest()->with('user');
        }]);

        return view('businesses.show', compact('business'));
    }

    public function category(Request $request, Category $category)
    {
        $category->load('subcategories');
        $categoryIds = $category->allCategoryIds();

        $query = Business::approved()->whereIn('category_id', $categoryIds)
            ->with(['subArea', 'reviews', 'category']);

        // Apply search query if provided
        if ($request->has('query') && $request->query) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }

        // Apply district filter if provided
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Apply sub-area filter if provided
        if ($request->has('sub_area_id') && $request->sub_area_id) {
            $query->where('sub_area_id', $request->sub_area_id);
        }

        $businesses = $query->get();

        // Apply manager rankings: top 10 ordered, rest random
        $rankings = $this->getRankingRowsForCategory($category->id);

        if ($rankings->isNotEmpty()) {
            $ranked = collect();
            $unranked = collect();

            foreach ($businesses as $business) {
                if ($rankings->has($business->id)) {
                    $business->manager_rank = $rankings[$business->id]->rank;
                    $ranked->push($business);
                } else {
                    $unranked->push($business);
                }
            }

            $ranked = $ranked->sortBy('manager_rank')->values();
            $unranked = $unranked->shuffle()->values();

            $businesses = $ranked->merge($unranked);
        } else {
            $businesses = $businesses->shuffle();
        }

        $categories = Category::all();
        $districts = District::all();

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('businesses._results', compact('businesses', 'categories', 'districts'))->render(),
                'count' => $businesses->count()
            ]);
        }

        return view('businesses.index', compact('businesses', 'category', 'categories', 'districts'));
    }

    public function featured()
    {
        $businesses = Business::approved()->where('is_featured', true)
            ->with(['category', 'subArea', 'reviews'])
            ->orderBy('featured_rank')
            ->take(25)
            ->get();

        return view('businesses.featured', compact('businesses'));
    }
}
