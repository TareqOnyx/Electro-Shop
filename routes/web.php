<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});



// Show all products
Route::get('/products', [ProductController::class, 'ShowProductPage'])->name('products');

// Add a new product
Route::post('/products/add', [ProductController::class, 'AddProduct'])->name('add-product');

// Update a product (usually via form with PUT method)
Route::post('/products/update/{id}', [ProductController::class, 'UpdateProduct'])->name('update-product');

// Delete a product
Route::get('/products/delete/{id}', [ProductController::class, 'DeleteProduct'])->name('delete-product');

