<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    public function index()
    {
        // Ambil produk dan kategorinya
        $products = Product::with('category')->get();
        return view('welcome', compact('products'));
    }

   public function store(Request $request)
{
    try {
        $request->validate([
            'total_price' => 'required',
            'items' => 'required'
        ]);

        // 1. Simpan Transaksi
        $transaction = Transaction::create([
            'total_price' => $request->total_price,
            'items' => $request->items,
        ]);

        // 2. Logika Kurangi Stok
        foreach ($request->items as $item) {
            $product = Product::where('name', $item['name'])->first();
            if ($product) {
                $product->decrement('stock', $item['qty']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dan stok diperbarui!',
            'transaction_id' => $transaction->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal: ' . $e->getMessage()
        ], 500);
    }
}
    public function history()
    {
        $transactions = Transaction::latest()->get();
        return view('history', compact('transactions'));
    }
}
