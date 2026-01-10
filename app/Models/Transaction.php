<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'items',
        'status',
        'invoice_number',
        'cash_received',
        'cash_change',
        'order_type',      // <--- TAMBAHKAN INI
        'payment_method'   // <--- TAMBAHKAN INI
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
