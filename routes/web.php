<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('index.dashboard');
Route::post('dashboard/list/product', [App\Http\Controllers\DashboardController::class, 'getProductData'])->name('dashboard.list.product');
Route::resource('product-category', App\Http\Controllers\ProductCategoryController::class);
Route::get('list/product-category', [App\Http\Controllers\ProductCategoryController::class, 'getProductCategoryData'])->name('list.product-category');
Route::resource('product', App\Http\Controllers\ProductController::class);
Route::get('list/product', [App\Http\Controllers\ProductController::class, 'getProductData'])->name('list.product');
Route::get('detail/product/{id}', [App\Http\Controllers\ProductController::class, 'getDetailProductData'])->name('detail.product');
