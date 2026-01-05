<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Izinkan kolom 'name' diisi secara massal
    protected $fillable = ['name'];

    // Relasi ke Product (Satu kategori punya banyak produk)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
