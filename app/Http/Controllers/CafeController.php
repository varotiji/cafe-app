<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CafeController extends Controller
{
    // 1. Menampilkan Halaman (Kasir, Customer, & API)
    public function index(Request $request)
    {
        $products = Product::with('category')->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        }

        if ($request->routeIs('customer.menu')) {
            return view('customer_menu', compact('products'));
        }

        return view('welcome', compact('products'));
    }

    // 2. Proses Transaksi
    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'total_price' => 'required|numeric',
                    'items' => 'required|array',
                ]);

                // Validasi Stok Sebelum Proses
                foreach ($request->items as $item) {
                    $product = Product::where('name', $item['name'])->first();
                    // Sesuaikan 'quantity' agar sinkron dengan JS
                    $jml = $item['quantity'] ?? 1;

                    if (!$product || $product->stock < $jml) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stok ' . ($item['name'] ?? 'Produk') . ' tidak cukup!'
                        ], 400);
                    }
                }

                $transaction = Transaction::create([
                    'user_id' => Auth::id() ?? 1,
                    'total_price' => $request->total_price,
                    'items' => $request->items,
                    'order_type' => $request->order_type ?? 'dine_in',
                    'status' => 'success',
                    'payment_method' => 'cash',
                ]);

                // Update Stok
                foreach ($request->items as $item) {
                    $jml = $item['quantity'] ?? 1;
                    Product::where('name', $item['name'])->decrement('stock', $jml);
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

    // 4. Menampilkan Dashboard Statistik (PERBAIKAN ERROR DI SINI)
 public function dashboard()
{
    // 1. Ambil data dasar
    $totalPendapatan = Transaction::sum('total_price');
    $totalTransaksi = Transaction::count();
    $transactions = Transaction::all();

    // 2. Inisialisasi variabel Shift
    $shiftPagi = 0;  // 06:00 - 14:59
    $shiftSiang = 0; // 15:00 - 18:59
    $shiftMalam = 0; // 19:00 - 05:59
    $produkTerjual = [];

    foreach ($transactions as $t) {
        // --- Logika Shift Berdasarkan Jam (created_at) ---
        $jam = $t->created_at->format('H'); // Ambil format 24 jam

        if ($jam >= 6 && $jam < 15) {
            $shiftPagi += $t->total_price;
        } elseif ($jam >= 15 && $jam < 19) {
            $shiftSiang += $t->total_price;
        } else {
            $shiftMalam += $t->total_price;
        }

        // --- Logika Menu Terlaris ---
        $items = $t->items;
        if (is_array($items)) {
            foreach ($items as $item) {
                $name = $item['name'] ?? 'Unknown';
                $qty = $item['quantity'] ?? ($item['qty'] ?? 0);
                if ($name !== 'Unknown') {
                    $produkTerjual[$name] = ($produkTerjual[$name] ?? 0) + $qty;
                }
            }
        }
    }

    arsort($produkTerjual);
    $bestSeller = array_slice($produkTerjual, 0, 5);

    return view('dashboard', compact(
        'totalPendapatan',
        'totalTransaksi',
        'bestSeller',
        'shiftPagi',
        'shiftSiang',
        'shiftMalam'
    ));
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
                        $jml = $item['quantity'] ?? ($item['qty'] ?? 0);
                        $product->increment('stock', $jml);
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
