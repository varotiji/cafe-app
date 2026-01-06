<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CafeController extends Controller
{
    // Menampilkan Halaman Kasir
    public function index()
    {
        $products = Product::with('category')->get();
        return view('welcome', compact('products'));
    }

    // Proses Transaksi
    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'total_price' => 'required|numeric',
                    'items' => 'required|array'
                ]);

                foreach ($request->items as $item) {
                    $product = Product::where('name', $item['name'])->first();
                    if (!$product || $product->stock < $item['qty']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stok ' . $item['name'] . ' tidak cukup!'
                        ], 400);
                    }
                }

                $transaction = Transaction::create([
                    'total_price' => $request->total_price,
                    'items' => $request->items,
                ]);

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
        });
    }

    // Menampilkan Riwayat
    public function history()
    {
        $transactions = Transaction::latest()->get();
        return view('history', compact('transactions'));
    }

    // Menampilkan Dashboard Statistik
    public function dashboard()
    {
        $totalPendapatan = Transaction::sum('total_price');
        $totalTransaksi = Transaction::count();
        $transactions = Transaction::all();
        $produkTerjual = [];

        foreach ($transactions as $t) {
            $items = is_string($t->items) ? json_decode($t->items, true) : $t->items;
            if (is_array($items)) {
                foreach ($items as $item) {
                    $name = $item['name'];
                    $qty = $item['qty'];
                    $produkTerjual[$name] = ($produkTerjual[$name] ?? 0) + $qty;
                }
            }
        }

        arsort($produkTerjual);
        $bestSeller = array_slice($produkTerjual, 0, 5);

        return view('dashboard', compact('totalPendapatan', 'totalTransaksi', 'bestSeller'));
    }

    // Membatalkan / Menghapus Transaksi & Kembalikan Stok
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            // Ambil data items
            $items = is_string($transaction->items) ? json_decode($transaction->items, true) : $transaction->items;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $product = Product::where('name', $item['name'])->first();
                    if ($product) {
                        $product->increment('stock', $item['qty']);
                    }
                }
            }

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
