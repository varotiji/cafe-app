<?php

use App\Http\Controllers\CafeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CafeController::class, 'index']);
Route::post('/checkout', [CafeController::class, 'store']);
Route::get('/history', [CafeController::class, 'history']);
