<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;

// ----------------------
// Public
// ----------------------

Route::get('/', fn () => view('welcome'))->name('home');

// ----------------------
// Auth (guest only: login, register, forgot, reset)
// ----------------------

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Register / Signup
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('register.store');

    // Login
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginSubmit')->name('login.submit');

    // Forgot password
    Route::get('/forgot', 'forgot')->name('forgot');
    Route::post('/forgot', 'sendResetLink')->name('password.email');

    // Reset password (form + submit)
    Route::get('/reset-password/{token}', 'resetForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// ----------------------
// Auth (authenticated: logout)
// ----------------------

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// ----------------------
// Email verification (signed link)
// ----------------------

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

// ----------------------
// Profile (auth required, grouped controller)
// ----------------------

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')->name('profile');
    Route::get('/profile/settings', 'edit')->name('profile.settings');
    Route::put('/profile', 'update')->name('profile.update');
    Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
});
