<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Izinkan kolom-kolom berikut diisi
  protected $fillable = ['name', 'price', 'stock', 'image', 'category'];

    // Relasi ke Category (Satu produk milik satu kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
