<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    public function __construct()
    {
        parent::__construct(); // BaseController ka constructor call hoga
        $this->middleware('auth'); // auth check
    }

    // Show Page
    public function index()
    {



    $customers = Customer::userData()->get();

        return view('customer.index', compact('customers'));
    }


    // Store
  public function store(Request $request)
{


    $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name'  => 'required|string|max:100',
        'phone'      => 'nullable|string|max:20',
        'email'      => 'nullable|email|unique:customers,email',
         'is_active' => 'required|in:0,1',
    ]);

    $data = $request->all();
    $data['user_id'] = auth()->id();

    Customer::create($data);

    return $this->getLatestCustomer(true, 'Customer created successfully.');
}
    private function getLatestCustomer($success = true, $message = 'Customer saved successfully!', $html = null)
    {

            $customers = Customer::userData()->get();


        if ($html === null) {
            $html = view('customer.data-table', compact('customers'))->render();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }

    // Edit
    public function edit($id)
    {


            $customer = Customer::userData()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    // Update
    public function update(Request $request, $id)
    {



        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|unique:customers,email,' . $id,
             'is_active' => 'required|in:0,1',
        ]);

            $customer = Customer::userData()->findOrFail($id);



        $customer->update($request->all());

        return $this->getLatestCustomer(true, 'Customer updated successfully.');
    }

    // Delete
    public function destroy($id)
    {



    $customer = Customer::userData()->findOrFail($id);
    $deleted = $customer->delete();



        $html = view('customer.data-table', compact('customers'))->render();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Customer deleted successfully' : 'Customer deletion failed',
            'html' => $html
        ]);
    }
}
