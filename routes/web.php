<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
Route::get('/admin/index', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');

Route::get('/admin/category-index', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.category-index');
Route::post('/admin/category-store', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.category-store');
Route::post('/admin/category-update/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.category-update');
Route::post('/admin/category-delete/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('admin.category-delete');

Route::get('/admin/product-index', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.product-index');
Route::post('/admin/product-store', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.product-store');
Route::post('/admin/product-update/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.product-update');
Route::post('/admin/product-delete/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('admin.product-delete');
