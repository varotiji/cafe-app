<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    public function index()
    {
        $menus = Menu::where('stock', '>', 0)->get();
        return view('transactions.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_price'    => 'required|numeric',
            'items'          => 'required|array',
            'order_type'     => 'required|string',
            'payment_method' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $subtotal = (int)$request->total_price;
                $pajak = round($subtotal * 0.11);
                $total_akhir = $subtotal + $pajak;

                $transaction = Transaction::create([
                    'user_id'        => Auth::id() ?? 1,
                    'total_price'    => $total_akhir,
                    'order_type'     => $request->order_type . ' (Meja: ' . ($request->table_number ?? '-') . ')',
                    'payment_method' => $request->payment_method,
                    'note'           => $request->note,
                    'items'          => json_encode($request->items),
                    'status'         => ($request->payment_method == 'Midtrans') ? 'pending' : 'success',
                ]);

                foreach ($request->items as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'menu_id'        => $item['id'],
                        'price'          => $item['price'],
                        'quantity'       => $item['quantity'],
                        'subtotal'       => $item['price'] * $item['quantity'],
                    ]);
                    $menu = Menu::find($item['id']);
                    if ($menu) { $menu->decrement('stock', $item['quantity']); }
                }

                $snapToken = null;
                if ($request->payment_method == 'Midtrans') {
                    $params = [
                        'transaction_details' => [
                            'order_id' => 'CAFE-' . $transaction->id . '-' . time(),
                            'gross_amount' => (int)$total_akhir,
                        ],
                        'item_details' => [
                            ['id' => 'SUB', 'price' => (int)$subtotal, 'quantity' => 1, 'name' => 'Subtotal'],
                            ['id' => 'TAX', 'price' => (int)$pajak, 'quantity' => 1, 'name' => 'PPN 11%'],
                        ],
                    ];
                    $snapToken = Snap::getSnapToken($params);
                    $transaction->update(['snap_token' => $snapToken]);
                }

                return response()->json(['status' => 'success', 'snap_token' => $snapToken, 'transaction_id' => $transaction->id]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function history()
    {
        $transactions = Transaction::with(['user', 'details.menu'])
                        ->latest()
                        ->paginate(10);
        return view('transactions.history', compact('transactions'));
    }

    public function print($id)
    {
        $transaction = Transaction::with(['details.menu', 'user'])->findOrFail($id);

        $qrContent = ($transaction->status == 'pending')
            ? "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $transaction->snap_token
            : "LUNAS-" . $transaction->id;

        $qrCode = QrCode::size(120)->generate($qrContent);
        $statusLabel = ($transaction->status == 'pending') ? "SCAN UNTUK BAYAR" : "LUNAS";

        return view('transactions.print', compact('transaction', 'qrCode', 'statusLabel'));
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $totalMenu = Menu::count();
        $transaksiHariIni = Transaction::whereDate('created_at', $today)->count();
        $pendapatanHariIni = Transaction::whereDate('created_at', $today)->sum('total_price') ?? 0;

        $labels = []; $totals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $totals[] = Transaction::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }
        return view('dashboard', compact('totalMenu', 'transaksiHariIni', 'pendapatanHariIni', 'labels', 'totals'));
    }
}
