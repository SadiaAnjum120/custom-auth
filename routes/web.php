<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\SuperAdminController;

// ----------------------
// Public
// ----------------------

Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/', fn () => view('welcome'))->name('home');

// ----------------------
// Auth (guest only: login, register, forgot, reset)
// Auth (guest only: login, register, forgot, reset)
// ----------------------

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Register / Signup
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('register.store');
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
// Auth (authenticated: logout)
// ----------------------

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// ----------------------
// Email verification (signed link)
// Email verification (signed link)
// ----------------------

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

// ----------------------
// Profile (auth required, grouped controller)
// Profile (auth required, grouped controller)
// ----------------------

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show')->name('profile');
    Route::get('/profile/settings', 'edit')->name('profile.settings');
    Route::put('/profile', 'update')->name('profile.update');
    Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
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
        Route::get('/subcategories/{categoryId}', 'getSubCategoriesByCategory')->name('product.subcategories');

});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/shops', [SuperAdminController::class, 'index'])->name('shops.index');

    Route::post('/shops/approve/{id}', [SuperAdminController::class, 'approve'])->name('shops.approve');

    Route::post('/shops/reject/{id}', [SuperAdminController::class, 'reject'])->name('shops.reject');

    Route::post('/shops/suspend/{id}', [SuperAdminController::class, 'suspend'])->name('shops.suspend');

});
use App\Http\Controllers\CustomerController;
Route::middleware('auth')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customers/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});

use App\Http\Controllers\OrdersController;

// ==========================
// ORDERS ROUTES
// ==========================
Route::middleware(['auth'])->group(function () {

    // Orders list page
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');

    // AJAX: Get subcategories by category
    Route::get('/sub-categories/{categoryId}', [OrdersController::class, 'getSubCategoriesByCategory']);

    // AJAX: Get products by subcategory
    Route::get('/products/{subCategoryId}', [OrdersController::class, 'getProductsBySubCategory']);

    // Store new order
    Route::post('/orders/store', [OrdersController::class, 'store'])->name('orders.store');

    // Edit order (AJAX)
    Route::get('/orders/edit/{order}', [OrdersController::class, 'edit'])->name('orders.edit');

    // Update order
    Route::put('/orders/update/{order}', [OrdersController::class, 'update'])->name('orders.update');

    // Delete order
    Route::delete('/orders/delete/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');

});
