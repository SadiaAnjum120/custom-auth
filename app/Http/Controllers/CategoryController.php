<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // INDEX (Only Logged-in User Data)

    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $categories = Category::all();
        } else {
            $categories = Category::where('user_id', auth()->id())->get();
        }

        return view('category.index', compact('categories'));
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        if ($category) {
            return $this->getLatestCategory(true, 'Category created successfully');
        } else {
            return $this->getLatestCategory(false, 'Category creation failed');
        }
    }


    // GET LATEST (AJAX TABLE REFRESH)

    private function getLatestCategory($success = true, $message = 'Category Saved successfully!', $html = null)
    {
        if (auth()->user()->is_admin) {
            $categories = Category::all();
        } else {
            $categories = Category::where('user_id', auth()->id())->get();
        }

        if ($html == null) {
            $html = view('category.data-table', compact('categories'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }


    // EDIT
    public function edit($id)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $category = Category::findOrFail($id);
        } else {
        $category = Category::where('user_id', auth()->id())
                            ->findOrFail($id);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
        ]);
if (auth()->check() && auth()->user()->is_admin) {
            $category = Category::findOrFail($id);
        } else {
        $category = Category::where('user_id', auth()->id())
                            ->findOrFail($id);
        }

        $validated['slug'] = Str::slug($validated['name']);

        $updated = $category->update($validated);

        if ($updated) {
            return $this->getLatestCategory(true, 'Category updated successfully');
        } else {
            return $this->getLatestCategory(false, 'Category update failed');
        }
    }

    // DELETE
   public function destroy($id)
{
    if (auth()->check() && auth()->user()->is_admin) {
        
        $category = Category::findOrFail($id);
        $deleted = $category->delete();

        $categories = Category::all();
    } else {
        
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $deleted = $category->delete();
    
        $categories = Category::where('user_id', auth()->id())->get();
    }

    $html = view('category.data-table', compact('categories'))->render();

    return response()->json([
        'success' => $deleted,
        'message' => $deleted ? 'Category deleted successfully' : 'Category deletion failed',
        'html' => $html
    ]);
}

}
