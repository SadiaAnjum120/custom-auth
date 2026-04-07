<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

class ProductController extends BaseController
{
     public function __construct()
    {
        parent::__construct(); // BaseController ka constructor call hoga
        $this->middleware('auth'); // auth check
    }
    // Check shop approval for shop admins


    // INDEX: Products Listing
    public function index()
    {

        $categories = Category::status()->userData()->get();
        $products = Product::with('category', 'subCategory')->userData()->get();

          return view('product.index', compact('products', 'categories'));
    }

    // GET SUBCATEGORIES BY CATEGORY (AJAX)
    public function getSubCategoriesByCategory($categoryId)
    {

            $subCategories = SubCategory::userData()->status()->where('category_id', $categoryId) ->get();

        return response()->json($subCategories);
    }

    // STORE NEW PRODUCT
    public function store(Request $request)
    {


        $validated = $request->validate([
             'name' => 'required|string|max:255|unique:products,name,NULL,id,user_id,' . auth()->id(),
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|in:0,1',


        ]);

        $validated['user_id'] = auth()->id();
        $validated['approval_status'] = 'pending'; // New products are pending by default

       do {
    $sku = 'SKU-' . strtoupper(\Illuminate\Support\Str::random(6));
} while (Product::where('sku', $sku)->exists());

        $validated['sku'] = $sku;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/products'), $fileName);
            $validated['image'] = $fileName;
        }

        $product = Product::create($validated);

        return $this->getLatestProducts(
            $product ? true : false,
            $product ? 'Product added successfully!' : 'Product creation failed'
        );
    }

    // GET LATEST PRODUCTS TABLE
    private function getLatestProducts($success = true, $message = 'Product saved successfully!', $html = null)
    {


            $products = Product::with('category', 'subCategory')->userData()->status()->get();


        if ($html === null) {
            $html = view('product.data-table', compact('products'))->render();
        }

        return response()->json(['success' => $success, 'message' => $message, 'html' => $html]);
    }

    // EDIT PRODUCT
    public function edit($id)
    {



            $product = Product::userData()->findOrFail($id);


        return response()->json(['success' => true, 'data' => $product]);
    }

    // UPDATE PRODUCT
    public function update(Request $request, $id)
    {


         $product = Product::userData()->findOrFail($id);

        $validated = $request->validate([
              'name' => 'required|string|max:255|unique:products,name,' . $id . ',id,user_id,' . auth()->id(),
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
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

        $currentStock = $product->quantity;
        $newStock = $currentStock;

        if ($request->filled('stock_action') && $request->filled('stock_quantity')) {
            $stockQty = (int) $request->stock_quantity;
            if ($request->stock_action === 'add') $newStock += $stockQty;
            if ($request->stock_action === 'subtract') {
                if ($stockQty > $currentStock) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['stock_quantity' => ['Quantity cannot be greater than current stock.']]
                    ], 422);
                }
                $newStock -= $stockQty;
            }
        }

        $validated['quantity'] = $newStock;
        $updated = $product->update($validated);

        return $this->getLatestProducts($updated, $updated ? 'Product updated successfully!' : 'Product update failed');
    }

    // DELETE PRODUCT
    public function destroy($id)
    {




           $product = Product::userData()->findOrFail($id);

        if ($product->image) {
            $imagePath = public_path('storage/products/' . $product->getOriginal('image'));
            if (file_exists($imagePath)) unlink($imagePath);
        }

        $deleted = $product->delete();

        return $this->getLatestProducts($deleted, $deleted ? 'Product deleted successfully' : 'Product deletion failed');
    }



    }
