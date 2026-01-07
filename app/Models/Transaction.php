<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi (Mass Assignment)
 protected $fillable = [
    'user_id', 'total_price', 'snap_token', 'status', 'payment_method', 'items'
];
    /**
     * Casting kolom 'items' agar otomatis menjadi Array.
     * Saat disimpan ke MySQL, Laravel akan mengubahnya jadi JSON.
     * Saat dibaca dari MySQL, Laravel akan mengubahnya jadi Array PHP.
     */
   // PENTING: Supaya database gak bingung cara simpan list makanan
protected $casts = [
    'items' => 'array',
];
}
