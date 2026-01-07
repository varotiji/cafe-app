<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Menu Kafe
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Tombol Tambah Menu --}}
                <a href="{{ route('menus.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded inline-block mb-4">
                    + Tambah Menu Baru
                </a>

                <table class="min-w-full mt-4 border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Nama</th>
                            <th class="border px-4 py-2 text-left">Harga</th>
                            <th class="border px-4 py-2 text-left">Kategori</th>
                            <th class="border px-4 py-2 text-center">Gambar</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $menu->name }}</td>
                            <td class="border px-4 py-2 text-green-600">Rp {{ number_format($menu->price) }}</td>
                            <td class="border px-4 py-2">{{ $menu->category }}</td>
                            <td class="border px-4 py-2 text-center">
                                @if($menu->image)
                                    <img src="{{ asset('storage/products/' . $menu->image) }}"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         class="rounded shadow-md mx-auto">
                                @else
                                    <span class="text-gray-400 text-xs">Tanpa Gambar</span>
                                @endif
                            </td>
{{-- Tombol Edit & Hapus --}}
<td class="border px-4 py-2 text-center">
    <div class="flex justify-center gap-2">
        {{-- Tombol Edit (KUNING) --}}
        <a href="{{ route('menus.edit', $menu->id) }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-xs font-black shadow-md uppercase">
            Edit
        </a>

        {{-- Tombol Hapus (MERAH) --}}
        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus menu ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-black shadow-md uppercase">
                Hapus
            </button>
        </form>
    </div>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
