<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    // 1. Tampilkan Daftar Menu
    public function index()
    {
        $menus = Menu::all();
        return view('menus.index', compact('menus'));
    }

    // 2. Form Tambah Menu
    public function create()
    {
        return view('menus.create');
    }

    // 3. Proses Simpan Menu Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/products', $filename);
        }

        Menu::create([
            'name' => $request->name,
            'description' => 'Menu baru',
            'price' => $request->price,
            'category' => $request->category,
            'image' => $filename,
            'is_available' => 1,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambah!');
    }

    // 4. Form Edit Menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    // 5. Proses Simpan Perubahan (Update)
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($menu->image && file_exists(storage_path('app/public/products/' . $menu->image))) {
                unlink(storage_path('app/public/products/' . $menu->image));
            }
            // Simpan foto baru
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/products', $filename);
            $menu->image = $filename;
        }

        $menu->update([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diupdate!');
    }

    // 6. Proses Hapus Menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->image && file_exists(storage_path('app/public/products/' . $menu->image))) {
            unlink(storage_path('app/public/products/' . $menu->image));
        }

        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
