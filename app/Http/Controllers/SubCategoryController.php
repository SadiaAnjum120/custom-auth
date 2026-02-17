<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    // INDEX (Only Logged-in User Data)
    
    public function index()
{
    if (auth()->user()->is_admin) {

        // Admin sees ALL data
        $categories = Category::where('is_active', 1)->get();

        $subCategories = SubCategory::with('category')->get();

    } else {

        // Normal user sees only own data
        $categories = Category::where('user_id', auth()->id())
                        ->where('is_active', 1)
                        ->get();

        $subCategories = SubCategory::with('category')
                            ->where('user_id', auth()->id())
                            ->get();
    }

    return view('sub-category.index', compact('subCategories','categories'));
}

    // STORE NEW SUBCATEGORY

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'is_active'   => 'required|in:0,1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);

        $subCategory = SubCategory::create($validated);

        if ($subCategory) {
            return $this->getLatestSubCategory(true, 'Sub Category created successfully');
        } else {
            return $this->getLatestSubCategory(false, 'Sub Category creation failed');
        }
    }

    // GET LATEST SUBCATEGORIES TABLE
    
    private function getLatestSubCategory($success = true, $message = 'Sub Category Saved successfully!', $html = null)
    {
        if (auth()->user()->is_admin) {
            $subCategories = SubCategory::with('category')->get();
        } else {
        $subCategories = SubCategory::with('category')

                            ->where('user_id', auth()->id())
                            ->get();
        }
        if ($html === null) {
            $html = view('sub-category.data-table', compact('subCategories'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html'    => $html
        ]);
    }

    
    // EDIT SUBCATEGORY
    public function edit($id)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $subCategory = SubCategory::with('category')->findOrFail($id);
        } else {
        $subCategory = SubCategory::where('user_id', auth()->id())
                            ->findOrFail($id);
        }

        return response()->json([
            'success' => true,
            'data'    => $subCategory
        ]);
    }

    // UPDATE SUBCATEGORY
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'is_active'   => 'required|in:0,1',
        ]);
        if (auth()->check() && auth()->user()->is_admin) {
            $subCategory = SubCategory::findOrFail($id);
        } else {
        $subCategory = SubCategory::where('user_id', auth()->id())
                            ->findOrFail($id);
        }

        $validated['slug'] = Str::slug($validated['name']);

        $updated = $subCategory->update($validated);

        if ($updated) {
            return $this->getLatestSubCategory(true, 'Sub Category updated successfully');
        } else {
            return $this->getLatestSubCategory(false, 'Sub Category update failed');
        }
    }

    
    // DELETE SUBCATEGORY
    
   public function destroy($id)
{
    if (auth()->check() && auth()->user()->is_admin) {
        // Admin can delete any sub-category
        $subCategory = SubCategory::findOrFail($id);
        $deleted = $subCategory->delete();

        // Reload all sub-categories for admin
        $subCategories = SubCategory::with('category')->get();
    } else {
        // Normal user can delete only their own sub-category
        $subCategory = SubCategory::where('user_id', auth()->id())->findOrFail($id);
        $deleted = $subCategory->delete();

        // Reload only user's sub-categories
        $subCategories = SubCategory::with('category')
                            ->where('user_id', auth()->id())
                            ->get();
    }

    $html = view('sub-category.data-table', compact('subCategories'))->render();

    return response()->json([
        'success' => $deleted,
        'message' => $deleted ? 'Sub Category deleted successfully' : 'Sub Category deletion failed',
        'html'    => $html
    ]);


        
    }
}
