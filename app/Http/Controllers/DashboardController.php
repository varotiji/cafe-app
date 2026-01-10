<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data Omzet 7 hari terakhir untuk Grafik
        $salesData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = $salesData->pluck('date');
        $totals = $salesData->pluck('total');

        // 2. Statistik Utama
        $totalMenu = Menu::count();
        $totalTransaksi = Transaction::count();
        $totalPendapatan = Transaction::sum('total_price') ?? 0;
        $pendapatanHariIni = Transaction::whereDate('created_at', today())->sum('total_price') ?? 0;
        $transaksiHariIni = Transaction::whereDate('created_at', today())->count();

        // 3. Pendapatan Per Shift (Dihitung berdasarkan LABEL SHIFT di profil Kasir)
        // Kita hubungkan (join) tabel transaksi dengan tabel users
        $shiftPagi = Transaction::whereHas('user', function($query) {
            $query->where('shift', 'pagi');
        })->whereDate('created_at', today())->sum('total_price') ?? 0;

        $shiftSiang = Transaction::whereHas('user', function($query) {
            $query->where('shift', 'sore'); // Sore/Siang disamakan sesuai label di DB kamu
        })->whereDate('created_at', today())->sum('total_price') ?? 0;

        $shiftMalam = Transaction::whereHas('user', function($query) {
            $query->where('shift', 'malam');
        })->whereDate('created_at', today())->sum('total_price') ?? 0;

        // 4. Best Seller (Dummy)
        $bestSeller = [
            'Es Kopi Susu Aren' => 45,
            'Matcha Latte' => 38,
            'Teh Manis' => 25
        ];

        return view('dashboard', compact(
            'totalMenu', 'totalTransaksi', 'totalPendapatan', 'pendapatanHariIni',
            'transaksiHariIni', 'shiftPagi', 'shiftSiang', 'shiftMalam',
            'labels', 'totals', 'bestSeller'
        ));
    }
}
