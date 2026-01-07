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
        // Mendefinisikan siapa yang boleh masuk Dashboard
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
