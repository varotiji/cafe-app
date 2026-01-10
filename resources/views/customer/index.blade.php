<!DOCTYPE html>
<html>
<head>
    <title>Menu Cafe Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <h1 class="text-2xl font-bold text-center mb-6">☕ Daftar Menu Cafe</h1>

    <div class="grid grid-cols-2 gap-4">
        @foreach($menus as $menu)
        <div class="bg-white p-3 rounded-lg shadow">
       <img src="{{ asset('storage/products/' . $menu->image) }}" class="w-full h-48 object-cover rounded shadow-md" alt="{{ $menu->name }}">
            <h2 class="font-bold mt-2">{{ $menu->name }}</h2>
            <p class="text-green-600">Rp {{ number_format($menu->price) }}</p>
        </div>
        @endforeach
    </div>
</body>
</html>
