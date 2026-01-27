<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    public function index() {
    $menus = \App\Models\Menu::where('stock', '>', 0)->get();
    // Arahkan ke view yang kita perbaiki gambarnya
    return view('menus.public', compact('menus'));
}
}
