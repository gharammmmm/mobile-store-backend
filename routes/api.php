<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;

// Auth
Route::post('/register',    [AuthController::class, 'register']);
Route::post('/login',       [AuthController::class, 'login']);
Route::post('/logout',      [AuthController::class, 'logout']);
Route::get('/auth-check',   [AuthController::class, 'check']);

// Mobiles
Route::get('/mobiles',      [MobileController::class, 'index']);
Route::get('/mobiles/{id}', [MobileController::class, 'show']);

// Reviews
Route::get('/reviews',      [ReviewController::class, 'index']);
Route::post('/reviews',     [ReviewController::class, 'store']);

// ✅ Favorites — مع /toggle مسار منفصل

Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);


