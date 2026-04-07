<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    // Show all shops
    public function index()
   {
    $user = auth()->user();

    if ($user->role !== 'super_admin') {
        abort(403, 'Access denied');
    }

    $shops = User::where('role', 'shop_admin')->get();
    return view('admin.shop.index', compact('shops'));
}
    // Approve shop
    public function approve($id)
    {
        $shop = User::findOrFail($id);

        $shop->approval_status = 'approved';
        $shop->is_active = true;
        $shop->save();

        // Optional: Send mail (commented to avoid errors during testing)
         Mail::raw('Congratulations! Your shop has been approved...', function($message) use($shop) {
            $message->to($shop->email)->subject('Shop Approved');
         });

        // Return JSON for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Shop approved successfully',
            'status_text' => 'Approved',
            'badge_class' => 'bg-success'
        ]);
    }

    // Reject shop
    public function reject($id)
    {
        $shop = User::findOrFail($id);

        $shop->approval_status = 'rejected';
        $shop->is_active = false;
        $shop->save();

        // Optional: Send mail
         Mail::raw('Sorry! Your shop request has been rejected by admin.', function($message) use($shop) {
            $message->to($shop->email)->subject('Shop Rejected');
         });

        return response()->json([
            'success' => true,
            'message' => 'Shop rejected successfully',
            'status_text' => 'Rejected',
            'badge_class' => 'bg-danger'
        ]);
    }

    // Suspend shop
    public function suspend($id)
    {
        $shop = User::findOrFail($id);

        $shop->approval_status = 'suspended';
        $shop->is_active = false;
        $shop->save();

        // Optional: Send mail
         Mail::raw('Your shop has been suspended by admin. Please contact support for more details.', function($message) use($shop) {
             $message->to($shop->email)->subject('Shop Suspended');
         });

        return response()->json([
            'success' => true,
            'message' => 'Shop suspended successfully',
            'status_text' => 'Suspended',
            'badge_class' => 'bg-secondary'
        ]);
    }
    // Impersonate shop
public function impersonate($id)
{
    $admin = auth()->user();

    // safety check
    if ($admin->role !== 'super_admin') {
        abort(403, 'Unauthorized');
    }

    $shop = User::findOrFail($id);
    // ✅ Only approved shops allowed
    if ($shop->approval_status !== 'approved') {
        return redirect()->back()->with('error', 'Only approved shops can be impersonated');
    }

    // store admin id in session
    session(['impersonator_id' => $admin->id]);

    // login as shop
    auth()->login($shop);

    return redirect('/'); // ya shop dashboard
}

// Stop impersonation
public function stopImpersonate()
{
    $adminId = session('impersonator_id');

    if ($adminId) {
        auth()->loginUsingId($adminId);
        session()->forget('impersonator_id');
    }

    return redirect('/admin/shops');
}
}
