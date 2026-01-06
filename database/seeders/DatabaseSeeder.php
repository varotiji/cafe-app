<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Admin
        User::create([
            'name' => 'Admin Cafe',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Bikin Kasir
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // Panggil seeder menu yang kemarin
        $this->call(CafeSeeder::class);
    }
}
