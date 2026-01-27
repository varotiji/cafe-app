<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', // Field ini wajib ada di fillable
        'menu_id',
        'price',
        'quantity',
        'subtotal'
    ];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
