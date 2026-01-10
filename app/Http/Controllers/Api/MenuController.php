<?php

namespace App\Http\Controllers\Api; // Alamat WAJIB beda (tambah \Api)

use App\Http\Controllers\Controller; // Panggil Controller Utama
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query(); // Biasanya API hanya menampilkan yang tidak dihapus

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $menus
        ], 200);
    }

    // Tambahkan fungsi store versi API jika memang butuh
    public function store(Request $request)
    {
        $menu = Menu::create($request->all());
        return response()->json($menu, 201);
    }
}
