<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Langsung ke Login)
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Pelanggan (Tanpa Login / Scan QR)
Route::get('/menu', [CustomerMenuController::class, 'index'])->name('customer.menu');
Route::post('/checkout-customer', [OrderController::class, 'checkout'])->name('checkout.customer');
Route::post('/checkout-cash', [OrderController::class, 'checkoutCash'])->name('checkout.cash');

// 3. SEMUA Route yang butuh Login (Admin/Kasir)
Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD (Grafik & Statistik)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Kasir / POS
    Route::get('/pos', [TransactionController::class, 'index'])->name('pos');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{id}/print', [TransactionController::class, 'print'])->name('transaksi.print');

    // Riwayat & Profile
    Route::get('/history', [TransactionController::class, 'history'])->name('history');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Menu (CRUD)
    Route::resource('menus', MenuController::class);
    // Route khusus Restore (Ditaruh di sini agar aman)
    Route::patch('/menus/{id}/restore', [MenuController::class, 'restore'])->name('menus.restore');

    // Khusus Admin Only
    Route::middleware('can:admin-only')->group(function () {
        Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
    });
});

require __DIR__.'/auth.php';
