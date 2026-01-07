<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin (Bebas Shift)
        User::create([
            'name' => 'Owner Cafe',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'shift' => null,
        ]);

        // 2. Kasir Pagi (06:00 - 14:00)
        User::create([
            'name' => 'andi Pagi',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'shift' => 'pagi',
        ]);

        // 3. Kasir Sore (14:00 - 22:00)
       User::create([
    'name' => 'Siti Admin',
    'email' => 'siti@gmail.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
]);

        // 4. Buat Kategori & Produk (Agar data tetap ada)
        $makanan = Category::firstOrCreate(['name' => '🍚 Makanan Berat']);
        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 25000,
            'stock' => 20,
            'image' => 'products/nasgor.jpg',
            'description' => 'Nasgor lezat dengan telur mata sapi.'
        ]);
    }
}
