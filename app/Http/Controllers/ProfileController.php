<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile (view only).
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.myprofile', compact('user'));
    }

    /**
     * Show the profile/settings edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.setting', compact('user'));
    }

    /**
     * Update profile (name, email, address, phone, etc.).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'address'      => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
        ]);

        $user->fill($validated)->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
