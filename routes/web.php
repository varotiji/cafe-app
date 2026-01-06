<?php

use App\Http\Controllers\CafeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CafeController::class, 'index']);
Route::post('/checkout', [CafeController::class, 'store']);
Route::get('/history', [CafeController::class, 'history']);
Route::get('/dashboard', [CafeController::class, 'dashboard']); // Lebih singkat karena sudah ada 'use' di atas
