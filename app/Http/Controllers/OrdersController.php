<?php
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;

class OrdersController extends BaseController
{

 public function __construct()
    {
        parent::__construct(); // BaseController ka constructor call hoga
        $this->middleware('auth'); // auth check
    }
    // ===============================
    // CHECK SHOP APPROVAL
    // ===============================


    // ===============================
    // INDEX
    // ===============================
    public function index()
    {


        $user = auth()->user();

       if ($user->is_admin == 1) {
    $categories = Category::where('is_active', 1)
        ->where(function($query) {
            // 1. Category ke direct products hain
            $query->whereHas('products', function($q) {
                $q->where('is_active', 1);
            })
            // 2. Ya subcategories ke paas products hain
            ->orWhereHas('subcategories.products', function($q){
                $q->where('is_active', 1);
            });
        })
        ->get();

    $customers = Customer::where('is_active',1)->get();

    $orders = Order::with(['customer','orderItems.product'])
        ->latest()
        ->get();
} else {
    $categories = Category::where('user_id', auth()->id())
        ->where('is_active', 1)
        ->where(function($query) {
            $query->whereHas('products', function($q){
                $q->where('is_active', 1)->where('quantity','>',0);
            })
            ->orWhereHas('subcategories.products', function($q){
                $q->where('is_active', 1)->where('quantity','>',0);
            });
        })
        ->get();

    $customers = Customer::where('user_id', auth()->id())
        ->where('is_active',1)
        ->get();

    $orders = Order::with(['customer','orderItems.product'])
        ->where('user_id',auth()->id())
        ->latest()
        ->get();
}
        return view('order.index',compact('orders','categories','customers'));
    }

    // ===============================
    // EDIT ORDER (FOR MODAL)
    // ===============================
 public function edit($id)
{
    $order = Order::with([
        'customer',
        'orderItems' => function($q){
            $q->with(['product','category','subCategory']);
        }
    ])->findOrFail($id);

    // Prepare raw order items for JS
    $orderItems = $order->orderItems->map(function($item){
        return [
            'id' => $item->id,
            'category_id' => $item->category_id,
            'sub_category_id' => $item->sub_category_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity
        ];
    });

    return response()->json([
        'success' => true,
        'order' => $order,
        'orderItems' => $orderItems
    ]);
}

    // ===============================
    // GET SUBCATEGORIES
    // ===============================
  public function getSubCategoriesByCategory($categoryId)
    {
        $query = SubCategory::where('category_id', $categoryId)
                    ->where('is_active', 1)
                    ->whereHas('products', function($q){
                        $q->where('quantity','>',0);
                    });

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        return response()->json($query->get());
    }

    // ===============================
    // GET PRODUCTS
    // ===============================
  public function getProductsBySubCategory($subCategoryId)
    {
        $query = Product::where('sub_category_id',$subCategoryId)
            ->where('is_active',1)
            ->where('quantity','>',0); // 🔥 KEY FIX

        if(!auth()->user()->is_admin){
            $query->where('user_id',auth()->id());
        }

        return response()->json($query->get());
    }

    // ===============================
    // STORE ORDER
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([

            'customer_id' => 'required|exists:customers,id',
            'order_status' => 'required|in:created,processing,completed,cancelled',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'category_id.*' => 'required|exists:categories,id',
            'sub_category_id.*' => 'required|exists:sub_categories,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0'
        ]);
         do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Order::where('order_number', $orderNumber)->exists());


        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'order_status' => $request->order_status,
            'payment_status' => 'unpaid',
            'order_number' => $orderNumber,
            'order_date' => now(),
            'total_amount' => 0
        ]);

        $totalAmount = 0;

        foreach($request->product_id as $index => $productId){

            $product = Product::findOrFail($productId);
            $qty = $request->quantity[$index];

            if($qty > $product->quantity){
                return response()->json([
                    'success'=>false,
                    'message'=>'Stock not available for '.$product->name
                ]);
            }

            OrderItem::create([
                'order_id'=>$order->id,
                'category_id'=>$request->category_id[$index],
                'sub_category_id'=>$request->sub_category_id[$index],
                'product_id'=>$productId,
                'quantity'=>$qty
            ]);

            $product->decrement('quantity',$qty);

            $totalAmount += $product->price * $qty;
        }

        // NEW FIELDS
        $subTotal = $totalAmount;
        $tax = $request->tax ?? 0;
        $discount = $request->discount ?? 0;

        $finalTotal = $subTotal + $tax - $discount;

        $paid = $request->paid_amount ?? 0;

        if($paid <= 0){
            $status = 'unpaid';
            $due = $finalTotal;
        }
        elseif($paid < $finalTotal){
            $status = 'partial';
            $due = $finalTotal - $paid;
        }
        else{
            $status = 'paid';
            $due = 0;
        }

        $order->update([
            'sub_total'=>$subTotal,
            'tax'=>$tax,
            'discount'=>$discount,
            'total_amount'=>$finalTotal,
            'paid_amount'=>$paid,
            'due_amount'=>$due,
            'notes'=>$request->notes,
            'payment_status'=>$status
        ]);

        return $this->getLatestOrders('Order created successfully.');
    }

    // ===============================
    // UPDATE ORDER
    // ===============================
    public function update(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if($order->order_status == 'completed'){
            return response()->json([
                'success'=>false,
                'message'=>'Completed orders cannot be edited.'
            ]);
        }

        $validated = $request->validate([
            'customer_id'=>'required|exists:customers,id',
            'order_status' => 'required|in:created,processing,completed,cancelled',
            'product_id.*'=>'required|exists:products,id',
            'quantity.*'=>'required|integer|min:1',
            'category_id.*'=>'required|exists:categories,id',
            'sub_category_id.*'=>'required|exists:sub_categories,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0'
        ]);

        foreach($order->orderItems as $item){
            if($item->product){
                $item->product->increment('quantity',$item->quantity);
            }
        }

        $order->orderItems()->delete();

        $totalAmount = 0;

        foreach($request->product_id as $index=>$productId){

            $product = Product::findOrFail($productId);
            $qty = $request->quantity[$index];

            if($qty > $product->quantity){
                return response()->json([
                    'success'=>false,
                    'message'=>'Stock not available for '.$product->name
                ]);
            }

            OrderItem::create([
                'order_id'=>$order->id,
                'category_id'=>$request->category_id[$index],
                'sub_category_id'=>$request->sub_category_id[$index],
                'product_id'=>$productId,
                'quantity'=>$qty
            ]);

            $product->decrement('quantity',$qty);

            $totalAmount += $product->price * $qty;
        }

        $subTotal = $totalAmount;
        $tax = $request->tax ?? 0;
        $discount = $request->discount ?? 0;

        $finalTotal = $subTotal + $tax - $discount;

        $paid = $request->paid_amount ?? 0;

        if($paid <= 0){
            $status = 'unpaid';
            $due = $finalTotal;
        }
        elseif($paid < $finalTotal){
            $status = 'partial';
            $due = $finalTotal - $paid;
        }
        else{
            $status = 'paid';
            $due = 0;
        }

        $order->update([
            'customer_id'=>$validated['customer_id'],
            'order_status'=>$validated['order_status'],
            'sub_total'=>$subTotal,
            'tax'=>$tax,
            'discount'=>$discount,
            'total_amount'=>$finalTotal,
            'paid_amount'=>$paid,
            'due_amount'=>$due,
            'notes'=>$request->notes,
            'payment_status'=>$status
        ]);

        return $this->getLatestOrders('Order updated successfully.');
    }

    // ===============================
    // DELETE ORDER
    // ===============================
  public function destroy($id)
{
    $order = Order::with('orderItems.product')->findOrFail($id);

    // ❌ Prevent delete if completed
    if ($order->order_status == 'completed') {
        return response()->json([
            'success' => false,
            'message' => 'Completed orders cannot be deleted.'
        ]);
    }

    // ✅ Restore stock
    foreach ($order->orderItems as $item) {
        if ($item->product) {
            $item->product->increment('quantity', $item->quantity);
        }
    }

    // ✅ DELETE ITEMS FIRST (IMPORTANT)
    $order->orderItems()->delete();

    // ✅ THEN DELETE ORDER
    $order->delete();

    // ✅ RETURN UPDATED TABLE
    $orders = Order::latest()->get();

    return response()->json([
        'success' => true,
        'message' => 'Order deleted successfully.',
        'html' => view('order.data-table', compact('orders'))->render()
    ]);
}

    // ===============================
    // LATEST ORDERS TABLE
    // ===============================
    private function getLatestOrders($message = 'Order saved successfully!')
    {
        $user = auth()->user();

        $orders = $user->is_admin
            ? Order::with(['customer', 'orderItems.product', 'orderItems.category', 'orderItems.subCategory'])->latest()->get()
            : Order::with(['customer', 'orderItems.product', 'orderItems.category', 'orderItems.subCategory'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();

        $html = view('order.data-table', compact('orders'))->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'html' => $html
        ]);
    }
}
