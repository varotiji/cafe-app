<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Hitung Ringkasan Statistik
        $totalMenu = Menu::count();
        $transaksiHariIni = Transaction::whereDate('created_at', $today)->count();
        $pendapatanHariIni = Transaction::whereDate('created_at', $today)->sum('total_price');

        // 2. Data Grafik (7 Hari Terakhir)
        $salesData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $labels = $salesData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $totals = $salesData->pluck('total')->toArray();

        // 3. Menu Terlaris (Top 5)
        // Jika kamu belum punya tabel transaction_details, ini akan mengambil data dummy agar tidak error
        $bestSeller = DB::table('transaction_details')
            ->join('menus', 'transaction_details.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(transaction_details.quantity) as total_qty'))
            ->groupBy('menus.name')
            ->orderBy('total_qty', 'DESC')
            ->take(5)
            ->pluck('total_qty', 'name')
            ->toArray();

        // 4. Hitung Pendapatan Berdasarkan Shift (Hari Ini)
        $shiftPagi = Transaction::whereDate('created_at', $today)
            ->whereTime('created_at', '>=', '06:00:00')
            ->whereTime('created_at', '<', '15:00:00')
            ->sum('total_price');

        $shiftSiang = Transaction::whereDate('created_at', $today)
            ->whereTime('created_at', '>=', '15:00:00')
            ->whereTime('created_at', '<', '19:00:00')
            ->sum('total_price');

        $shiftMalam = Transaction::whereDate('created_at', $today)
            ->where(function($query) {
                $query->whereTime('created_at', '>=', '19:00:00')
                      ->orWhereTime('created_at', '<', '05:00:00');
            })
            ->sum('total_price');

        return view('dashboard', compact(
            'totalMenu', 'transaksiHariIni', 'pendapatanHariIni',
            'labels', 'totals', 'bestSeller',
            'shiftPagi', 'shiftSiang', 'shiftMalam'
        ));
    }
}
