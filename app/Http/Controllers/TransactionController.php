<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Menampilkan menu yang stoknya lebih dari 0 saja untuk POS
        $menus = Menu::where('stock', '>', 0)->get();
        return view('transactions.index', compact('menus'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'total_price'    => 'required|numeric',
            'items'          => 'required|array',
            'order_type'     => 'required|string', // Dine In, Take Away, dll
            'payment_method' => 'required|string', // Tunai, QRIS, dll
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 2. Simpan Transaksi Utama ke Tabel Transactions
                $transaction = Transaction::create([
                    'user_id'        => Auth::id() ?? 1,
                    'total_price'    => $request->total_price,
                    'order_type'     => $request->order_type,     // Kolom baru
                    'payment_method' => $request->payment_method, // Kolom baru
                    'items'          => json_encode($request->items),
                    'status'         => 'success',
                ]);

                // 3. Simpan Detail ke Tabel TransactionDetail & Potong Stok
                foreach ($request->items as $item) {
                    // Cek ID Menu (Pastikan frontend mengirim 'menu_id' atau 'id')
                    $menuId = $item['menu_id'] ?? $item['id'];

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'menu_id'        => $menuId,
                        'quantity'       => $item['quantity'] ?? 1,
                        'price'          => $item['price'],
                    ]);

                    // Potong Stok di Tabel Menus
                    $menu = Menu::find($menuId);
                    if ($menu) {
                        $menu->decrement('stock', $item['quantity'] ?? 1);
                    }
                }

                return response()->json([
                    'message' => 'Transaksi berhasil!',
                    'transaction_id' => $transaction->id
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function history()
    {
        // Mengambil riwayat transaksi terbaru
        $transactions = Transaction::latest()->get();
        return view('history', compact('transactions'));
    }

    public function print($id)
    {
        // Mengambil data transaksi beserta detail menu untuk struk
        $transaction = Transaction::with('details.menu')->findOrFail($id);
        return view('transactions.print', compact('transaction'));
    }

    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();
            return response()->json(['status' => 'success', 'message' => 'Transaksi Berhasil Dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
