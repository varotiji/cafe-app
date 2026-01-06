<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT KATEGORI
        $catMakanan = Category::create(['name' => '🍚 Makanan Berat']);
        $catMie     = Category::create(['name' => '🍜 Mie & Bakso']);
        $catSnack   = Category::create(['name' => '🍢 Snack & Camilan']);
        $catWestern = Category::create(['name' => '🍔 Western Food']);
        $catDessert = Category::create(['name' => '🧁 Dessert & Minum']);

        // --- DATA MAKANAN BERAT ---
        $makananBerat = [
            'Nasi Goreng Spesial', 'Nasi Goreng Kampung', 'Nasi Goreng Seafood', 'Nasi Goreng Ayam',
            'Nasi Goreng Sosis', 'Nasi Goreng Bakso', 'Nasi Goreng Pete', 'Nasi Goreng Rendang',
            'Nasi Goreng Mawut', 'Nasi Goreng Korea', 'Nasi Ayam Geprek', 'Nasi Ayam Crispy',
            'Nasi Ayam Bakar', 'Nasi Ayam Goreng', 'Nasi Ayam Teriyaki', 'Nasi Ayam Blackpepper',
            'Nasi Ayam Saus Mentega', 'Nasi Ayam Lada Hitam', 'Nasi Ayam Rica-rica', 'Nasi Ayam Sambal Matah',
            'Nasi Bebek Goreng', 'Nasi Bebek Bakar', 'Nasi Bebek Madura', 'Nasi Rendang', 'Nasi Rawon',
            'Nasi Soto Ayam', 'Nasi Soto Daging', 'Nasi Pecel', 'Nasi Campur', 'Nasi Uduk', 'Nasi Kuning',
            'Nasi Liwet', 'Nasi Kebuli', 'Nasi Briyani', 'Nasi Goreng Jepang', 'Nasi Kare Ayam',
            'Nasi Kare Jepang', 'Nasi Katsu Ayam', 'Nasi Katsu Saus Keju', 'Nasi Katsu Sambal'
        ];
        foreach ($makananBerat as $item) {
            Product::create(['category_id' => $catMakanan->id, 'name' => $item, 'price' => rand(15, 35) * 1000, 'stock' => rand(10, 30)]);
        }

        // --- DATA MIE & BAKSO ---
        $mieBakso = [
            'Mie Goreng', 'Mie Rebus', 'Mie Goreng Jawa', 'Mie Nyemek', 'Mie Aceh Goreng', 'Mie Aceh Kuah',
            'Mie Ayam Original', 'Mie Ayam Bakso', 'Mie Ayam Jamur', 'Mie Ayam Pedas', 'Mie Ayam Yamin',
            'Bakso Urat', 'Bakso Halus', 'Bakso Telur', 'Bakso Jumbo', 'Bakso Mercon', 'Bakso Bakar',
            'Bakso Aci', 'Bakso Kuah Pedas', 'Bakso Campur'
        ];
        foreach ($mieBakso as $item) {
            Product::create(['category_id' => $catMie->id, 'name' => $item, 'price' => rand(12, 25) * 1000, 'stock' => rand(15, 40)]);
        }

        // --- DATA SNACK & CAMILAN ---
        $snack = [
            'Kentang Goreng', 'Kentang Goreng Keju', 'Kentang Goreng BBQ', 'Kentang Goreng Balado',
            'Sosis Goreng', 'Sosis Bakar', 'Nugget Ayam', 'Nugget Keju', 'Cireng', 'Cireng Isi',
            'Cilok', 'Cilok Kuah', 'Cimol', 'Seblak Original', 'Seblak Ceker', 'Seblak Sosis',
            'Seblak Seafood', 'Tahu Crispy', 'Tahu Walik', 'Tempe Mendoan', 'Tempe Goreng',
            'Pisang Goreng', 'Pisang Goreng Keju', 'Pisang Goreng Coklat', 'Singkong Goreng',
            'Singkong Keju', 'Ubi Goreng', 'Bakwan', 'Risol Mayo', 'Lumpia Goreng'
        ];
        foreach ($snack as $item) {
            Product::create(['category_id' => $catSnack->id, 'name' => $item, 'price' => rand(8, 18) * 1000, 'stock' => rand(20, 50)]);
        }

        // --- DATA WESTERN FOOD ---
        $western = [
            'Burger Original', 'Burger Keju', 'Burger Beef', 'Burger Ayam Crispy', 'Hotdog',
            'Sandwich Telur', 'Sandwich Ayam', 'Kebab Mini', 'Kebab Jumbo', 'Pizza Mini',
            'Pizza Sosis', 'Pizza Keju', 'Spaghetti Bolognese', 'Spaghetti Carbonara',
            'Spaghetti Aglio Olio', 'Chicken Wings', 'Chicken Wings BBQ', 'Chicken Wings Pedas',
            'Fish and Chips', 'Chicken Popcorn'
        ];
        foreach ($western as $item) {
            Product::create(['category_id' => $catWestern->id, 'name' => $item, 'price' => rand(20, 45) * 1000, 'stock' => rand(10, 25)]);
        }

        // --- DATA DESSERT & MINUMAN ---
        $dessert = [
            'Es Teh Manis', 'Es Teh Lemon', 'Es Jeruk', 'Es Milo', 'Es Coklat', 'Es Cappuccino',
            'Es Matcha', 'Es Taro', 'Es Vanilla Latte', 'Es Kopi Susu', 'Es Kopi Gula Aren',
            'Milkshake Coklat', 'Milkshake Vanilla', 'Milkshake Stroberi', 'Jus Alpukat',
            'Jus Mangga', 'Jus Jeruk', 'Jus Melon', 'Jus Semangka', 'Es Buah', 'Pudding Coklat',
            'Pudding Strawberry', 'Pudding Caramel', 'Donat Gula', 'Donat Coklat', 'Donat Keju',
            'Brownies', 'Cheesecake', 'Churros', 'Waffle', 'Pancake', 'Martabak Mini',
            'Martabak Coklat', 'Martabak Keju', 'Martabak Keju Coklat', 'Roti Bakar Coklat',
            'Roti Bakar Keju', 'Roti Bakar Kacang', 'Es Krim Vanilla', 'Es Krim Coklat'
        ];
        foreach ($dessert as $item) {
            Product::create(['category_id' => $catDessert->id, 'name' => $item, 'price' => rand(5, 25) * 1000, 'stock' => rand(30, 60)]);
        }
    }
}
