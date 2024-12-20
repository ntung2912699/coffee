<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    Route::get('/admin/category-index', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.category-index');
    Route::post('/admin/category-store', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.category-store');
    Route::post('/admin/category-update/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.category-update');
    Route::post('/admin/category-delete/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('admin.category-delete');

    Route::get('/admin/product-index', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.product-index');
    Route::post('/admin/product-store', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.product-store');
    Route::post('/admin/product-update/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.product-update');
    Route::post('/admin/product-delete/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('admin.product-delete');

    Route::get('admin/orders/index', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders-index');
    Route::get('/orders/print/{id}', [\App\Http\Controllers\HomeController::class, 'printOrder'])->name('orders.print');
    Route::get('/orders/printReceipt/{id}', [\App\Http\Controllers\HomeController::class, 'printReceipt'])->name('orders.printReceipt');
    Route::post('/orders/cancel/{id}', [\App\Http\Controllers\HomeController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/delete/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'delete'])->name('orders.delete');
});

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
Route::get('/categories/{id}/products', [\App\Http\Controllers\HomeController::class, 'getProductsByCategory'])->name('product-by-category');
Route::post('/orders/store', [\App\Http\Controllers\HomeController::class, 'storeOrder'])->name('orders.store');
Route::get('/order-success/{id}', [\App\Http\Controllers\HomeController::class, 'orderSuccess'])->name('orders.success');
Route::get('/order/{id}', [\App\Http\Controllers\HomeController::class, 'show'])->name('orders.show');
Route::get('/product-options', [\App\Http\Controllers\HomeController::class, 'getOptions'])->name('product-options.show');
Route::get('/search-products', [\App\Http\Controllers\HomeController::class, 'searchProducts'])->name('product-search');



require __DIR__.'/auth.php';
