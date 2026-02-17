<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // INDEX (Only Logged-in User Data )
   public function index()
{
    if (auth()->check() && auth()->user()->is_admin) {
        $categories = Category::where('is_active', 1)->get();
        $subCategories = SubCategory::where('is_active', 1)->get();
        $products = Product::with('category', 'subCategory')->get();
    } else {
        $categories = Category::where('user_id', auth()->id())
                              ->where('is_active', 1)
                              ->get();

        $subCategories = SubCategory::where('user_id', auth()->id())
                                    ->where('is_active', 1)
                                    ->get();

        $products = Product::with('category', 'subCategory')
                           ->where('user_id', auth()->id())
                           ->get();
    }

    return view('product.index', compact('products', 'categories', 'subCategories'));
}

    // STORE NEW PRODUCT

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|in:0,1',
        ]);

        $validated['user_id'] = auth()->id();


        do {
            $sku = 'SKU-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (Product::where('sku', $sku)->where('user_id', auth()->id())->exists());

        $validated['sku'] = $sku;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/products'), $fileName);
            $validated['image'] = $fileName;
        }

        $product = Product::create($validated);

        if ($product) {
            return $this->getLatestProducts(true, 'Product added successfully!');
        } else {
            return $this->getLatestProducts(false, 'Product creation failed');
        }
    }


    // GET LATEST PRODUCTS TABLE

    private function getLatestProducts($success = true, $message = 'Product saved successfully!', $html = null)
    {
        if (auth()->user()->is_admin) {
            $products = Product::with('category', 'subCategory')->get();
        } else {
        $products = Product::with('category', 'subCategory')
                    ->where('user_id', auth()->id())
                    ->get();
        }

        if ($html === null) {
            $html = view('product.data-table', compact('products'))->render();
        }

        return response()->json(['success' => $success, 'message' => $message, 'html' => $html]);
    }


    // Edit Product
    public function edit($id)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $product = Product::findOrFail($id);
        } else {
        $product = Product::where('user_id', auth()->id())
                    ->findOrFail($id);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }


    // UPDATE PRODUCT

    public function update(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $product = Product::findOrFail($id);
        } else {
        $product = Product::where('user_id', auth()->id())
                    ->findOrFail($id);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|in:0,1',
        ]);

       if ($request->hasFile('image')) {
    $oldImage = $product->getOriginal('image');

    if ($oldImage && file_exists(public_path('storage/products/'.$oldImage))) {
        unlink(public_path('storage/products/'.$oldImage));
    }

    $file = $request->file('image');
    $fileName = time().'_'.$file->getClientOriginalName();
    $file->move(public_path('storage/products'), $fileName);

    $validated['image'] = $fileName;
}

        $updated = $product->update($validated);

        if ($updated) {
            return $this->getLatestProducts(true, 'Product updated successfully!');
        } else {
            return $this->getLatestProducts(false, 'Product update failed');
        }
    }


   // DELETE PRODUCT
public function destroy($id)
{
    if (auth()->check() && auth()->user()->is_admin) {
        $product = Product::findOrFail($id);
    } else {
        $product = Product::where('user_id', auth()->id())->findOrFail($id);
    }

    // Delete image first
  if ($product->image) {
        $imagePath = public_path('storage/products/' . $product->getOriginal('image'));
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Delete product only once
    $deleted = $product->delete();

    // Reload products
    if (auth()->user()->is_admin) {
        $products = Product::with('category', 'subCategory')->get();
    } else {
        $products = Product::with('category', 'subCategory')
                    ->where('user_id', auth()->id())
                    ->get();
    }

    $html = view('product.data-table', compact('products'))->render();

    return response()->json([
        'success' => $deleted,
        'message' => $deleted ? 'Product deleted successfully' : 'Product deletion failed',
        'html' => $html
    ]);
}
}

