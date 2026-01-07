<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index() {
        // Ini yang akan dipanggil oleh aplikasi HP/Scan QR nantinya
        return response()->json([
            'status' => 'success',
            'data' => Menu::where('is_available', true)->get()
        ]);
    }
}
