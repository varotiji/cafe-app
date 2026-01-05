<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori
        $makanan = Category::create(['name' => 'Makanan']);
        $minuman = Category::create(['name' => 'Minuman']);

        // 2. Buat Produk Makanan
        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 25000,
            'stock' => 20
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name' => 'Mie Goreng Jawa',
            'price' => 20000,
            'stock' => 15
        ]);

        // 3. Buat Produk Minuman
        Product::create([
            'category_id' => $minuman->id,
            'name' => 'Es Kopi Susu',
            'price' => 15000,
            'stock' => 50
        ]);

        Product::create([
            'category_id' => $minuman->id,
            'name' => 'Teh Manis Dingin',
            'price' => 5000,
            'stock' => 100
        ]);
    }
}
