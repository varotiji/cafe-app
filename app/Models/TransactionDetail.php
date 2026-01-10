<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'menu_id', 'quantity', 'price'];

    // TAMBAHKAN INI: Supaya Detail Transaksi kenal sama tabel Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
