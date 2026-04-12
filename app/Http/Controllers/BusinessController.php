<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\District;
use App\Models\SubArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    public function search(Request $request)
    {
        $query = Business::query()->with(['category', 'subArea', 'reviews']);

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
        })->sortByDesc('ranking_score');

        $categories = Category::all();
        $districts = District::all();

        return view('businesses.index', compact('businesses', 'categories', 'districts'));
    }

    public function apiSearch(Request $request)
    {
        $query = Business::query()->with(['category', 'subArea', 'reviews']);

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
        })->sortByDesc('ranking_score');

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
        $business->increment('views_count');
        $business->load(['category', 'subArea', 'reviews' => function($query) {
            $query->latest()->with('user');
        }]);
        
        return view('businesses.show', compact('business'));
    }

    public function category(Request $request, Category $category)
    {
        $query = Business::where('category_id', $category->id)
            ->with(['subArea', 'reviews']);

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
        $businesses = Business::where('is_featured', true)
            ->with(['category', 'subArea', 'reviews'])
            ->orderBy('featured_rank')
            ->take(25)
            ->get();

        return view('businesses.featured', compact('businesses'));
    }
}
