<?php

use App\Http\Controllers\CafeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

// 1. Halaman Utama
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route yang butuh Login (Untuk Admin/Kasir)
Route::middleware('auth')->group(function () {
    Route::get('/pos', [CafeController::class, 'index'])->name('pos');
    Route::get('/history', [CafeController::class, 'history'])->name('history');

    // Ini checkout untuk Kasir (Bayar Tunai)
    Route::post('/checkout-pos', [CafeController::class, 'checkout'])->name('checkout.pos');

    Route::delete('/transaction/{id}', [CafeController::class, 'destroy'])->name('transaction.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// 3. Route khusus Admin
Route::get('/dashboard', [CafeController::class, 'dashboard'])
    ->middleware(['auth', 'can:admin-only'])
    ->name('dashboard');

// 4. Route untuk Pelanggan (Tanpa Login / Scan QR)
Route::get('/menu-customer', [CafeController::class, 'index'])->name('customer.menu');

// Route untuk QRIS
Route::post('/checkout-customer', [OrderController::class, 'checkout'])->name('checkout.customer');

// Route untuk Cash
Route::post('/checkout-cash', [OrderController::class, 'checkoutCash'])->name('checkout.cash');

Route::resource('menus', MenuController::class)->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
