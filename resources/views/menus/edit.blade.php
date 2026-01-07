<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Menu: {{ $menu->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Form mengarah ke route update dengan ID menu --}}
                <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- WAJIB untuk proses Update di Laravel --}}

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Nama Menu</label>
                        <input type="text" name="name" value="{{ $menu->name }}" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ $menu->price }}" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500">
                            <option value="Minuman" {{ $menu->category == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Makanan" {{ $menu->category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Ganti Foto Menu (Opsional)</label>
                        <div class="mt-2 mb-2">
                            <p class="text-xs text-gray-500 mb-1">Foto saat ini:</p>
                            <img src="{{ asset('storage/products/' . $menu->image) }}" width="80" class="rounded border shadow-sm">
                        </div>
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-bold shadow transition duration-150">
                            Update Menu
                        </button>
                        <a href="{{ route('menus.index') }}" class="text-gray-500 hover:text-gray-700">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
