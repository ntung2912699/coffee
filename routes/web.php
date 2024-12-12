<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/index', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
