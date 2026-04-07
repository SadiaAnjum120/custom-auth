<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct(); // BaseController ka constructor call hoga
        $this->middleware('auth'); // auth check
    }
    // INDEX (Only Logged-in User Data)
  public function index()
{
    // Fetch categories
    $categories = Category::with([])
        ->userData()
        ->status()
        ->get();


    $subCategories = SubCategory::with('category')
        ->userData()
        ->status()
        ->get();

    return view('sub-category.index', compact('subCategories', 'categories'));
}


    // STORE NEW SUBCATEGORY
    public function store(Request $request)
    {


        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories,name,NULL,id,user_id,' . auth()->id(),
            'is_active'   => 'required|in:0,1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        $subCategory = SubCategory::create($validated);

        if ($subCategory) {
            return $this->getLatestSubCategory(true, 'Sub Category created successfully');
        } else {
            return $this->getLatestSubCategory(false, 'Sub Category creation failed');
        }
    }

    // GET LATEST SUBCATEGORIES TABLE (AJAX)
    private function getLatestSubCategory($success = true, $message = 'Sub Category saved successfully!', $html = null)
    {

        $subCategories = SubCategory::with('category')
            ->userData()
            ->status()
            ->get();

        if ($html === null) {
            $html = view('sub-category.data-table', compact('subCategories'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html'    => $html
        ]);
    }
private function generateUniqueSlug($name)
{
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    while (SubCategory::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count;
        $count++;
    }

    return $slug;
}
    // EDIT SUBCATEGORY
    public function edit($id)
    {


       $subCategory = SubCategory::with('category')->userData()->findOrFail($id);

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
              'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,user_id,' . auth()->id(),
            'is_active'   => 'required|in:0,1',
        ]);


      $subCategory = SubCategory::with('category')->userData()->findOrFail($id);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

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

     $subCategory = SubCategory::userData()->findOrFail($id);
    $deleted = $subCategory->delete();

    $subCategories = SubCategory::with('category')->userData()->get();

        $html = view('sub-category.data-table', compact('subCategories'))->render();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Sub Category deleted successfully' : 'Sub Category deletion failed',
            'html'    => $html
        ]);
    }
}
