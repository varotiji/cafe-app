<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Menu::create([
        'name' => 'Burger', 'price' => 13000, 'stock' => 100, 'category' => 'Makanan', 'image' => 'burger.jpg'
    ]);
    \App\Models\Menu::create([
        'name' => 'Pizza', 'price' => 20000, 'stock' => 100, 'category' => 'Makanan', 'image' => 'pizza.jpg'
    ]);
    \App\Models\Menu::create([
        'name' => 'Nasi Goreng', 'price' => 10000, 'stock' => 85, 'category' => 'Makanan', 'image' => 'nasigoreng.jpg'
    ]);
}
}
