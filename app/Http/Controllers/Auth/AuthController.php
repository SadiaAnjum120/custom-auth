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
    // -------------------------------------------------------------------------
    // Register / Signup
    // -------------------------------------------------------------------------

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'address'    => 'nullable|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $validated['is_active'] = false;
        $validated['email_verified_at'] = null;

        $user = User::create($validated);
        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Please verify your email before login.');
    }

    // -------------------------------------------------------------------------
    // Login / Logout
    // -------------------------------------------------------------------------

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'This email is not registered. Please create an account.');
        }

        if (!Auth::attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            return back()->with('error', 'Invalid email or password.');
        }

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->with('error', 'Please verify your email before login.');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Login successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }

    // -------------------------------------------------------------------------
    // Forgot password
    // -------------------------------------------------------------------------

    public function forgot()
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link has been sent to your email.')
            : back()->with('error', 'This email is not registered.');
    }

    public function resetForm(string $token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful. Please login.')
            : back()->with('error', 'Invalid or expired reset link.');
    }

    // -------------------------------------------------------------------------
    // Email verification
    // -------------------------------------------------------------------------

    public function verifyEmail(string $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            abort(403, 'Invalid verification link');
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->is_active = true;
            $user->save();
        }

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now login.');
    }
}
