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

        // CEK STOK DULU SEBELUM SIMPAN
        foreach ($request->items as $item) {
            $product = Product::where('name', $item['name'])->first();
            if (!$product || $product->stock < $item['qty']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok ' . $item['name'] . ' tidak cukup! (Sisa: ' . ($product->stock ?? 0) . ')'
                ], 400); // Kirim error jika stok kurang
            }
        }

        // Jika stok aman, baru simpan transaksi
        $transaction = Transaction::create([
            'total_price' => $request->total_price,
            'items' => $request->items,
        ]);

        // Kurangi Stok
        foreach ($request->items as $item) {
            Product::where('name', $item['name'])->decrement('stock', $item['qty']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil!',
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

    // Fungsi Dashboard dipindahkan ke dalam class
    public function dashboard()
    {
        $totalPendapatan = Transaction::sum('total_price');
        $totalTransaksi = Transaction::count();

        // Mengambil semua transaksi untuk menghitung produk terlaris
        $transactions = Transaction::all();
        $produkTerjual = [];

        foreach ($transactions as $t) {
            // Memastikan items di-decode jika tersimpan sebagai JSON string
            $items = is_string($t->items) ? json_decode($t->items, true) : $t->items;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $name = $item['name'];
                    $qty = $item['qty'];
                    $produkTerjual[$name] = ($produkTerjual[$name] ?? 0) + $qty;
                }
            }
        }

        arsort($produkTerjual); // Urutkan dari yang terbanyak
        $bestSeller = array_slice($produkTerjual, 0, 5); // Ambil 5 teratas

        return view('dashboard', compact('totalPendapatan', 'totalTransaksi', 'bestSeller'));
    }

    public function destroy($id)
{
    try {
        $transaction = Transaction::findOrFail($id);

        // BALIKIN STOK SEBELUM DIHAPUS
        foreach ($transaction->items as $item) {
            $product = Product::where('name', $item['name'])->first();
            if ($product) {
                $product->increment('stock', $item['qty']);
            }
        }

        // HAPUS TRANSAKSINYA
        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi dihapus & stok telah dikembalikan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menghapus: ' . $e->getMessage()
        ], 500);
    }
}

}
