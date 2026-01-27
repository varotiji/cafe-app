<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Pastikan ini ada

class Menu extends Model
{
    use HasFactory, SoftDeletes; // Gunakan trait ini

    protected $fillable = ['name', 'category', 'price', 'stock', 'image'];
}
