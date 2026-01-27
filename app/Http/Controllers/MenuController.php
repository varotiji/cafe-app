<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan Menu yang AKTIF saja
    public function index(Request $request) {
        $query = Menu::query(); // Laravel otomatis filter yang deleted_at-nya NULL

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->latest()->get();
        return view('menus.index', compact('menus'));
    }

    public function create() {
        return view('menus.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'category' => 'required|in:Makanan,Minuman,Snack',
            'image'    => 'nullable|image|mimes:jpg,png,jpeg|max:5120'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);
        return redirect()->route('menus.index')->with('success', 'Menu Berhasil Ditambah!');
    }

    public function edit($id) {
        $menu = Menu::findOrFail($id); // Hanya bisa edit menu yang belum dihapus
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, $id) {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'category' => 'required|in:Makanan,Minuman,Snack',
            'image'    => 'nullable|image|mimes:jpg,png,jpeg|max:5120'
        ]);

        $menu->name = $request->name;
        $menu->category = $request->category;
        $menu->price = $request->price;
        $menu->stock = $request->stock;

        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->image = $request->file('image')->store('menus', 'public');
        }

        $menu->save();
        return redirect()->route('menus.index')->with('success', 'Menu Berhasil Diperbarui!');
    }

    public function destroy($id) {
        $menu = Menu::findOrFail($id);
        $menu->delete(); // Soft Delete: Isi kolom deleted_at di DB, data hilang dari web

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
