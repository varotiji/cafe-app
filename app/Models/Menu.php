<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * fillable: daftar kolom yang diizinkan untuk diisi secara massal.
     * Ini penting supaya Poin 1 (Gambar) dan Poin 8 (Deskripsi) bisa masuk ke database.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'is_available'
    ];
}
