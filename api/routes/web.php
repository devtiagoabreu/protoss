<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [Site\HomeController::class, 'index']);

Route::prefix('painel')->group(function(){
    Route::get('/', [Admin\HomeController::class, 'index']);
});
