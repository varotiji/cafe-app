<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

   public function boot(): void
{
    // Tambahin baris ini biar Laravel maksa semua link pake HTTPS kalo di Ngrok
    if (config('app.env') !== 'local' || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}
