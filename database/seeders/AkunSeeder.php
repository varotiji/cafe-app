<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

class AkunSeeder extends Seeder {
    public function run(): void {
        // Buat Admin
        User::updateOrCreate(['email' => 'admin@cafe.com'], [
            'name' => 'Si Boss Admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
        // Buat Kasir
        User::updateOrCreate(['email' => 'kasir@cafe.com'], [
            'name' => 'Staf Kasir',
            'password' => bcrypt('kasir123'),
            'role' => 'kasir'
        ]);
    }
}
