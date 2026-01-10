<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu yang ada di database
        $menus = Menu::all();
        return view('customer.index', compact('menus'));
    }
}
