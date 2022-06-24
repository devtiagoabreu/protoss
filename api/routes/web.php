<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', [SiteController::class, 'index']);

Route::prefix('admin')->group(function(){
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('login', [LoginController::class, 'index'])->name('login');

});
