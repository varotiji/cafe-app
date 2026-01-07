<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Menu Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label>Nama Menu</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label>Harga</label>
                        <input type="number" name="price" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div class="mb-4">
                        <label>Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded">
                            <option value="Minuman">Minuman</option>
                            <option value="Makanan">Makanan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label>Foto Menu</label>
                        <input type="file" name="image" class="w-full" required>
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan Menu</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
