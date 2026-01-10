<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('✏️ Edit Menu: ' . $menu->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

               <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-bold mb-2">Nama Menu</label>
        <input type="text" name="name" value="{{ old('name', $menu->name) }}" class="w-full border-gray-300 rounded shadow-sm" required>
    </div>

    <div class="mb-4">
        <label class="block font-bold mb-2">Kategori</label>
        <select name="category" class="w-full border-gray-300 rounded shadow-sm" required>
            <option value="Makanan" {{ old('category', $menu->category) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
            <option value="Minuman" {{ old('category', $menu->category) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
            <option value="Snack" {{ old('category', $menu->category) == 'Snack' ? 'selected' : '' }}>Snack</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block font-bold mb-2">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $menu->price) }}" class="w-full border-gray-300 rounded shadow-sm" required>
        </div>
        <div>
            <label class="block font-bold mb-2">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $menu->stock) }}" class="w-full border-gray-300 rounded shadow-sm" required>
        </div>
    </div>

    <div class="mb-6">
        <label class="block font-bold mb-2">Ganti Foto (Opsional)</label>
        @if($menu->image)
            <img src="{{ asset('storage/' . $menu->image) }}" class="w-24 h-24 object-cover mb-2 rounded border">
        @endif
        <input type="file" name="image" class="w-full text-sm text-gray-500">
    </div>

    <div class="flex justify-end gap-2 border-t pt-4">
        <a href="{{ route('menus.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
        <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded font-bold shadow">
            SIMPAN PERUBAHAN
        </button>
    </div>
</form>

            </div>
        </div>
    </div>
</x-app-layout>
