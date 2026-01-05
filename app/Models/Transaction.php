<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Mengizinkan kolom ini untuk diisi data
    protected $fillable = ['total_price', 'items'];

    /**
     * Casting kolom 'items' agar otomatis menjadi array
     * saat diambil dari database (sangat penting!)
     */
    protected $casts = [
        'items' => 'array',
    ];
}
