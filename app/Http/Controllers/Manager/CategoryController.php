<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Business;
use App\Models\CategoryBusinessRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Get main categories (where parent_id is null)
        $query = Category::whereNull('parent_id')
            ->with(['subcategories' => function($q) {
                $q->withCount(['businesses', 'subcategories']);
            }])
            ->withCount(['businesses', 'subcategories']);

        // Search functionality - search in both main and subcategories
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('subcategories', function($qs) use ($search) {
                      $qs->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $mainCategories = $query->latest()->paginate(20)->withQueryString();
        
        return view('manager.categories.index', compact('mainCategories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('manager.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->only(['name', 'parent_id']));

        return redirect()->route('manager.categories.index')->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category)
    {
        // Get potential parent categories (excluding self and children to prevent circular references)
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
        
        return view('manager.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id,
        ]);

        // Prevent setting a subcategory as parent (only main categories can be parents)
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->parent_id !== null) {
                return back()->with('error', __('Cannot set a subcategory as parent. Only main categories can have subcategories.'));
            }
        }

        $category->update($request->only(['name', 'parent_id']));

        return redirect()->route('manager.categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category)
    {
        // Check if category has subcategories
        if ($category->subcategories()->count() > 0) {
            return back()->with('error', __('Cannot delete category with subcategories. Delete subcategories first.'));
        }

        // Check if category has businesses
        if ($category->businesses()->count() > 0) {
            return back()->with('error', __('Cannot delete category with businesses. Move or delete businesses first.'));
        }

        $category->delete();

        return redirect()->route('manager.categories.index')->with('success', __('Category deleted successfully.'));
    }

    public function rankings(Category $category)
    {
        $category->load('subcategories');
        $categoryIds = $category->allCategoryIds();

        $businesses = Business::approved()->whereIn('category_id', $categoryIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $rankings = CategoryBusinessRanking::where('category_id', $category->id)
            ->orderBy('rank')
            ->get()
            ->keyBy('rank');

        return view('manager.categories.rankings', compact('category', 'businesses', 'rankings'));
    }

    public function updateRankings(Request $request, Category $category)
    {
        $request->validate([
            'rankings' => 'nullable|array',
            'rankings.*' => 'nullable|exists:businesses,id',
        ]);

        $rankings = $request->input('rankings', []);
        $selectedIds = array_filter($rankings);

        if (count($selectedIds) !== count(array_unique($selectedIds))) {
            return back()->with('error', __('Each place can only be selected once across all rankings.'));
        }

        DB::transaction(function () use ($rankings, $category) {
            CategoryBusinessRanking::where('category_id', $category->id)->delete();

            $insertData = [];

            foreach ($rankings as $rank => $businessId) {
                if ($businessId) {
                    $insertData[] = [
                        'category_id' => $category->id,
                        'business_id' => $businessId,
                        'rank' => (int) $rank,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($insertData)) {
                CategoryBusinessRanking::insert($insertData);
            }
        });

        return redirect()->route('manager.categories.rankings', $category)
            ->with('success', __('Rankings updated successfully.'));
    }
}
