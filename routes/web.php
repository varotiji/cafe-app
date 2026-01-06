<?php

use App\Http\Controllers\CafeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Langsung lempar ke login kalau belum masuk)
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route yang butuh Login (Auth)
Route::middleware('auth')->group(function () {

    // Halaman Kasir / POS
    Route::get('/pos', [CafeController::class, 'index'])->name('pos');

    // Halaman History (Cukup tulis satu kali di sini)
    Route::get('/history', [CafeController::class, 'history'])->name('history');

    // Proses Bayar
    Route::post('/checkout', [CafeController::class, 'checkout'])->name('checkout');

    // Proses Hapus Transaksi (Dipanggil oleh JavaScript di halaman history)
    Route::delete('/transaction/{id}', [CafeController::class, 'destroy'])->name('transaction.destroy');

    // Halaman Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. Route khusus Admin (Dashboard)
Route::get('/dashboard', [CafeController::class, 'dashboard'])
    ->middleware(['auth', 'can:admin-only'])
    ->name('dashboard');

require __DIR__.'/auth.php';
