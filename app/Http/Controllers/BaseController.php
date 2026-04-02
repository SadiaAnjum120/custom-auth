<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = Auth::user();

            if (!$user) {
                // Agar login nahi hai, redirect login
                return redirect()->route('login');
            }

            // ==============================
            // Super Admin: sirf shops dekhe
            // ==============================
            if ($user->role === 'super_admin') {
                // agar super admin hai, baki content block
                abort(403, 'Unauthorized access.');
            }

            // =====================================
            // Shop Admin: approval check
            // =====================================
            if ($user->role === 'shop_admin' && $user->approval_status !== 'approved') {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Your shop is ' . $user->approval_status . '. Access denied.');
            }

            // =====================================
            // Admin (is_admin = 1): full access
            // =====================================
            if ($user->is_admin) {
                view()->share('isAdmin', true);
            }

            return $next($request);
        });
    }

    /**
     * Helper method: check if current user is super admin
     */
    protected function isSuperAdmin()
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }

    /**
     * Helper method: check if current user is shop admin
     */
    protected function isShopAdmin()
    {
        return Auth::check() && Auth::user()->role === 'shop_admin';
    }

    /**
     * Helper method: check if current user is full admin
     */
    protected function isAdmin()
    {
        return Auth::check() && Auth::user()->is_admin;
    }
}
