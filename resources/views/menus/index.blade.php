<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('📜 Kelola Daftar Menu Kafe') }}
            </h2>
            <a href="{{ route('menus.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all active:scale-95">
                + TAMBAH MENU BARU
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md font-bold text-center">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-100">
                <form action="{{ route('menus.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-gray-600 text-sm font-bold mb-2 uppercase tracking-wide">Cari Nama Menu</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Contoh: Kopi Susu..."
                               class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>

                    <div class="w-full md:w-64">
                        <label class="block text-gray-600 text-sm font-bold mb-2 uppercase tracking-wide">Filter Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="">Semua Jenis</option>
                            <option value="Makanan" {{ request('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ request('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Snack" {{ request('category') == 'Snack' ? 'selected' : '' }}>Snack</option>
                        </select>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="bg-gray-800 text-white px-8 py-2.5 rounded-lg hover:bg-black font-bold transition-all shadow-md">
                            CARI
                        </button>
                        @if(request('search') || request('category'))
                            <a href="{{ route('menus.index') }}" class="bg-red-50 text-red-600 px-4 py-2.5 rounded-lg border border-red-200 hover:bg-red-100 font-bold transition-all">
                                RESET
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider">Gambar</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider">Nama Menu</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider text-center">Harga</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider text-center">Stok</th>
                            <th class="px-6 py-4 font-bold uppercase text-sm tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($menus as $menu)
                        <tr class="{{ $menu->trashed() ? 'bg-red-50' : 'hover:bg-gray-50' }} transition-colors">
                            <td class="px-6 py-4">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="w-20 h-16 object-cover rounded-xl shadow-sm border-2 border-white {{ $menu->trashed() ? 'grayscale' : '' }}">
                                @else
                                    <div class="w-20 h-16 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-xs italic text-center">No Image</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black {{ $menu->trashed() ? 'text-gray-400' : 'text-gray-800' }} text-lg">{{ $menu->name }}</div>
                                <div class="text-xs font-bold text-blue-500 uppercase tracking-tighter">{{ $menu->category }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($menu->trashed())
                                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold uppercase">Non-Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold uppercase">Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="{{ $menu->trashed() ? 'text-gray-400' : 'text-green-600' }} font-bold text-lg">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-600">
                                {{ $menu->stock }} <span class="text-[10px] text-gray-400">Porsi</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($menu->trashed())
                                        <form action="{{ route('menus.restore', $menu->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-green-700 transition shadow-sm uppercase">Aktifkan</button>
                                        </form>
                                    @else
                                        <a href="{{ route('menus.edit', $menu->id) }}" class="bg-orange-500 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-orange-600 transition shadow-sm">EDIT</a>
                                        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan menu {{ $menu->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-red-700 transition shadow-sm uppercase">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-500 font-bold italic text-xl">
                                🥤 Menu tidak ditemukan...
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
