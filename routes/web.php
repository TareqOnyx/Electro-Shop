<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Public Routes (Frontend)
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية → عرض الكاتيجوري + المنتجات
Route::get('/', [DashboardController::class, 'showCategories'])->name('home');

// عرض كل المنتجات
Route::get('/products', [DashboardController::class, 'showProducts'])->name('products.list');

// عرض منتج واحد
Route::get('/products/{id}', [DashboardController::class, 'showSingleProduct'])->name('products.show');

// عرض منتجات حسب الكاتيجوري
Route::get('/category/{id}', [DashboardController::class, 'showCategoryProducts'])->name('category.products');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Dashboard & Management)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::post('/categories', [DashboardController::class, 'addCategory'])->name('categories.store');
    Route::put('/categories/{id}', [DashboardController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('categories.destroy');

    // Products CRUD
    Route::post('/products', [DashboardController::class, 'addProduct'])->name('products.store');
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [DashboardController::class, 'deleteProduct'])->name('products.destroy');

    // Areas CRUD
    Route::post('/areas', [DashboardController::class, 'addArea'])->name('areas.store');
    Route::put('/areas/{id}', [DashboardController::class, 'updateArea'])->name('areas.update');
    Route::delete('/areas/{id}', [DashboardController::class, 'deleteArea'])->name('areas.destroy');

    // Orders management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatusAdmin'])->name('orders.updateStatus');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
