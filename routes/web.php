<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;




use App\Models\User;

// ----------------------
// Public routes
// ----------------------

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'loginSubmit'])
    ->middleware('guest')
    ->name('login.submit');

Route::get('/forgot', [AuthController::class, 'forgot'])
    ->middleware('guest')
    ->name('forgot');



Route::get('/forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::post('/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ----------------------
// Email verification
// ----------------------

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = User::findOrFail($id);

    // verify hash
    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $hash
    )) {
        abort(403, 'Invalid verification link');
    }

    // verify + activate
    if (is_null($user->email_verified_at)) {
        $user->email_verified_at = now();
        $user->is_active = true;
        $user->save();
    }

    return redirect()->route('login')
        ->with('success', 'Email verified successfully! You can now login.');

})->middleware('signed')->name('verification.verify');

Route::get('/profile', function () {
    return view('profile.myprofile');
})->name('profile');
