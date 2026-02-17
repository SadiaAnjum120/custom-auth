<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

// ----------------------
// Public
// ----------------------

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ----------------------
// Auth: Register / Signup
// ----------------------

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

// ----------------------
// Auth: Login / Logout
// ----------------------

Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'loginSubmit'])
    ->middleware('guest')
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ----------------------
// Auth: Forgot password
// ----------------------

Route::get('/forgot', [AuthController::class, 'forgot'])
    ->middleware('guest')
    ->name('forgot');

Route::post('/forgot', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ----------------------
// Auth: Email verification
// ----------------------

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

// ----------------------
// Profile (auth required)
// ----------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.settings');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
Route::controller(CategoryController::class)->middleware('auth')->prefix('category')->group(function () {
    Route::get('/', 'index')->name('category.index');
    Route::post('/add', 'store')->name('category.store');
    Route::get('/edit/{id}', 'edit')->name('category.edit');
    Route::put('/update/{id}', 'update')->name('category.update');
    Route::delete('/destroy/{id}', 'destroy')->name('category.delete');
});
use App\Http\Controllers\SubCategoryController;

Route::controller(SubCategoryController::class)->middleware('auth')->prefix('subcategory')->group(function () {

    Route::get('/', 'index')->name('subcategory.index');

    Route::post('/store', 'store')->name('subcategory.store');

    Route::get('/edit/{id}', 'edit')->name('subcategory.edit');

    Route::put('/update/{id}', 'update')->name('subcategory.update');

    Route::delete('/delete/{id}', 'destroy')->name('subcategory.delete');

});
use App\Http\Controllers\ProductController;

Route::controller(ProductController::class)->middleware('auth')->prefix('product')->group(function () {


    Route::get('/', 'index')->name('product.index');
    Route::post('/store', 'store')->name('product.store');
    Route::get('/edit/{id}', 'edit')->name('product.edit');
    Route::put('/update/{id}', 'update')->name('product.update');
    Route::delete('/delete/{id}', 'destroy')->name('product.destroy');

});
