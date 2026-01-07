<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CafeController extends Controller
{
    // 1. Menampilkan Halaman (Kasir, Customer, & API)
    public function index(Request $request)
    {
        $products = Product::with('category')->get();

        // Cek jika permintaan adalah API (Poin 4 - Microservices)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        }

        // Cek jika yang buka adalah Customer lewat scan barcode (Poin 7)
        if ($request->routeIs('customer.menu')) {
            return view('customer_menu', compact('products'));
        }

        // Tampilan standar untuk Kasir
        return view('welcome', compact('products'));
    }

    // 2. Proses Transaksi (Sudah mendukung integrasi masa depan)
    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'total_price' => 'required|numeric',
                    'items' => 'required|array',
                    'order_type' => 'nullable|string' // Tambahan untuk Poin 10 (Gofood dll)
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
                    'order_type' => $request->order_type ?? 'dine_in', // Default makan di tempat
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

    // 3. Menampilkan Riwayat
    public function history()
    {
        $transactions = Transaction::latest()->get();
        return view('history', compact('transactions'));
    }

    // 4. Menampilkan Dashboard Statistik
    public function dashboard()
    {
        $totalPendapatan = Transaction::sum('total_price');
        $totalTransaksi = Transaction::count();
        $transactions = Transaction::all();
        $produkTerjual = [];

        foreach ($transactions as $t) {
            $items = $t->items; // Karena sudah pakai $casts di Model, tidak perlu json_decode manual
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

    // 5. Hapus Transaksi & Kembalikan Stok
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $items = $transaction->items;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $product = Product::where('name', $item['name'])->first();
                    if ($product) {
                        $product->increment('stock', $item['qty']);
                    }
                }
            }

            $transaction->delete();
            return response()->json(['status' => 'success', 'message' => 'Transaksi dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
