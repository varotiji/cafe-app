<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
{
    \App\Models\User::create([
        'name' => 'Kasir Pagi',
        'email' => 'pagi@cafe.com',
        'password' => bcrypt('pagi123'),
        'role' => 'kasir'
    ]);
    \App\Models\User::create([
        'name' => 'Kasir Siang',
        'email' => 'siang@cafe.com',
        'password' => bcrypt('siang123'),
        'role' => 'kasir'
    ]);
    \App\Models\User::create([
        'name' => 'Kasir Malam',
        'email' => 'malam@cafe.com',
        'password' => bcrypt('malam123'),
        'role' => 'kasir'
    ]);
}
}
