<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import ini

class Menu extends Model
{
    use SoftDeletes; // 2. Gunakan ini

    protected $fillable = ['name', 'description', 'price', 'image', 'category', 'stock', 'is_available'];
}
