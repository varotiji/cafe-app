<?php

use App\Http\Controllers\{ProfileController, MenuController, CustomerMenuController, TransactionController, OrderController, DashboardController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

// 1. REDIRECT UTAMA
Route::get('/', function () {
    return redirect()->route('login');
});

// Webhook Midtrans
Route::post('/webhook/midtrans', [WebhookController::class, 'handle']);

// 2. ROUTE PUBLIK
Route::get('/menu-digital', [TransactionController::class, 'publicMenu'])->name('menu.digital');
Route::get('/menu', [CustomerMenuController::class, 'index'])->name('customer.menu');
Route::post('/checkout-customer', [OrderController::class, 'checkout'])->name('checkout.customer');
Route::post('/checkout-cash', [OrderController::class, 'checkoutCash'])->name('checkout.cash');

// 3. ROUTE PRIVAT (Wajib Login)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/pos', [TransactionController::class, 'index'])->name('pos');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');

    // NAMA ROUTE DISAMAKAN DENGAN BLADE (transactions.print)
    Route::get('/transaksi/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('/history', [TransactionController::class, 'history'])->name('history');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // 4. KHUSUS ADMIN (Middleware: admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::controller(MenuController::class)->group(function () {
            Route::get('/menus', 'index')->name('menus.index');
            Route::get('/menus/create', 'create')->name('menus.create');
            Route::post('/menus', 'store')->name('menus.store');
            Route::get('/menus/{id}/edit', 'edit')->name('menus.edit');
            Route::put('/menus/{id}', 'update')->name('menus.update');
            Route::delete('/menus/{id}', 'destroy')->name('menus.destroy');
            Route::post('/menus/{id}/restore', 'restore')->name('menus.restore');
        });
    });
});

require __DIR__.'/auth.php';
