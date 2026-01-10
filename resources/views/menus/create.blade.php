<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('➕ Tambah Menu Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-orange-500 text-white p-3 rounded mb-4 text-sm font-bold">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Menu</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Kopi Susu Aren" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price') }}" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="15000" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Stok Awal</label>
                            <input type="number" name="stock" value="{{ old('stock') }}" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="50" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Menu</label>
                        <input type="file" name="image" class="w-full text-sm text-gray-500">
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('menus.index') }}" class="text-gray-600 font-bold px-4 py-2 hover:underline">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2 px-6 rounded-lg shadow-lg">
                            SIMPAN MENU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
