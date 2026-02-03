<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function forgot()
    {
        return view('auth.forgot');
    }

    // -------------------------
    // REGISTER
    // -------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'address'    => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        // force defaults
        $validated['is_active'] = false;
        $validated['email_verified_at'] = null;

        $user = User::create($validated);



        // ✅ sirf verification email
        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Please verify your email before login.');
    }

    // -------------------------
    // LOGIN
    // -------------------------
  // ✅ add this at top

public function loginSubmit(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    // 1️⃣ check user exists
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'This email is not registered. Please create an account.');
    }

    // 2️⃣ attempt login
    if (!Auth::attempt(
        ['email' => $request->email, 'password' => $request->password],
        $request->remember
    )) {
        return back()->with('error', 'Invalid email or password.');
    }

    // 3️⃣ email verification check (MOST IMPORTANT)
    if (!$user->hasVerifiedEmail()) {
        Auth::logout(); // 🔥 THIS WAS MISSING

        return back()->with('error', 'Please verify your email before login.');
    }

    // ✅ success
    return redirect()->route('home')
        ->with('success', 'Login successful!');
}





/* 📧 SEND RESET LINK */
public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Password reset link has been sent to your email.')
        : back()->with('error', 'This email is not registered.');
}

/* 🔑 RESET FORM */
public function resetForm(string $token)
{
    return view('auth.reset', ['token' => $token]);
}

/* 🔄 UPDATE PASSWORD */
public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset successful. Please login.')
        : back()->with('error', 'Invalid or expired reset link.');
}

}
