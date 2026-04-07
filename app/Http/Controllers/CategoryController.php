<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct(); // BaseController ka constructor call hoga
        $this->middleware('auth'); // auth check
    }

    // ------------------------------
    // INDEX (Only Logged-in User Data)
    // ------------------------------
    public function index()
    {



        $categories = Category::userData()->get(); // Scope method se data fetch

        return view('category.index', compact('categories'));
    }

    // ------------------------------
    // STORE
    // ------------------------------
    public function store(Request $request)
    {


        $validated = $request->validate([
         'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,' . auth()->id(),
            'is_active' => 'required|in:0,1',
        ]);

        $validated['user_id'] = auth()->id();
     $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $category = Category::create($validated);

        return $this->getLatestCategory($category ? true : false, $category ? 'Category created successfully' : 'Category creation failed');
    }
private function generateUniqueSlug($name)
{
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    while (Category::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count;
        $count++;
    }

    return $slug;
}
    // ------------------------------
    // GET LATEST (AJAX TABLE REFRESH)
    // ------------------------------
    private function getLatestCategory($success = true, $message = 'Category saved successfully!', $html = null)
    {


         $categories = Category::userData()->get(); // Scope method se data fetch

        if ($html === null) {
            $html = view('category.data-table', compact('categories'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }

    // ------------------------------
    // EDIT
    // ------------------------------
    public function edit($id)
    {




         $category = Category::userData()->findOrFail($id); // Scope method se data fetch

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // ------------------------------
    // UPDATE
    // ------------------------------
    public function update(Request $request, $id)
    {


        $validated = $request->validate([
          'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,user_id,' . auth()->id(),
            'is_active' => 'required|in:0,1',
        ]);



            $category = Category::userData()->findOrFail($id); // Scope method se data fetch



        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $updated = $category->update($validated);

        return $this->getLatestCategory($updated, $updated ? 'Category updated successfully' : 'Category update failed');
    }

    // ------------------------------
    // DELETE
    // ------------------------------
    public function destroy($id)
    {


        $category = Category::userData()->findOrFail($id);
        $deleted = $category->delete();
        $categories = Category::userData()->get();

        $html = view('category.data-table', compact('categories'))->render();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Category deleted successfully' : 'Category deletion failed',
            'html' => $html
        ]);
    }
}
