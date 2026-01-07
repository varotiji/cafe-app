<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Pastikan baris di bawah ini ada agar Laravel tahu di mana lokasi CafeController
use App\Http\Controllers\CafeController;
use App\Http\Controllers\Api\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Endpoint untuk ambil menu (Poin 4 - Restful API)
Route::get('/products', [CafeController::class, 'index']);

// Endpoint untuk simpan pesanan dari Frontend/Scan Barcode (Poin 7)
Route::post('/order', [CafeController::class, 'checkout']);

Route::get('/menus', [MenuController::class, 'index']);
