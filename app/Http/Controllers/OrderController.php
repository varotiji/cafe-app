<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    // 1. Fungsi untuk QRIS (Midtrans)
    public function checkout(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'QRIS-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan QRIS',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

      Transaction::create([
    'total_price' => $request->total_price,
    'status' => 'success',
    'payment_method' => 'cash',
    'items' => $request->items,
    'snap_token' => null
]);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 2. Fungsi untuk Bayar CASH (Taruh di sini)
    public function checkoutCash(Request $request)
    {
        try {
            Transaction::create([
                'user_id' => 1,
                'total_price' => $request->total_price,
                'status' => 'success',
                'payment_method' => 'cash',
                'items' => $request->items, // Ini yang bikin riwayat ada rincian makanannya
                'snap_token' => null
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
