<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;


Route::get('/ping', function(){
    return ['pong'=>true];
});


Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

Route::post('/user', [AuthController::class, 'create']);

Route::post('/lead', [AuthController::class, 'createLead']);


Route::put('/user', [UserController::class, 'update']);
Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
Route::post('/user/cover', [UserController::class, 'updateCover']);

Route::get('/feed', [FeedController::class, 'read']);
Route::get('/user/feed', [FeedController::class, 'userFeed']);
Route::get('/user/photos', [FeedController::class, 'userPhotos']);

Route::get('/user/{id}/feed', [FeedController::class, 'userFeed']);
Route::post('/user/{id}/follow', [UserController::class, 'follow']);
Route::get('/user/{id}/followers', [UserController::class, 'followers']);
Route::get('/user/{id}/photos', [FeedController::class, 'userPhotos']);

Route::get('/user', [UserController::class, 'read']);
Route::get('/user/{id}', [UserController::class, 'read']);

Route::post('/feed', [FeedController::class, 'create']);

Route::post('/post/{id}/like', [PostController::class, 'like']);
Route::post('/post/{id}/comment', [PostController::class, 'comment']);

Route::get('/search', [SearchController::class, 'search']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

