<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Digital - Cafe Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Plus_Jakarta_Sans']">

    <div class="bg-white p-5 shadow-sm sticky top-0 z-50 text-center">
        <h1 class="text-xl font-extrabold text-gray-800">☕ Daftar Menu <span class="text-orange-600">Cafe Premium</span></h1>
    </div>

    <div class="p-4 max-w-md mx-auto mb-20">
        <div class="grid grid-cols-1 gap-6">
            @foreach($menus as $menu)
            <div class="bg-white rounded-3xl overflow-hidden shadow-md border border-gray-100">
                <div class="h-56 w-full bg-gray-200">
                    {{-- CEK APAKAH ADA GAMBAR --}}
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}"
                             alt="{{ $menu->name }}"
                             class="w-full h-full object-cover">
                    @else
                        {{-- GAMBAR CADANGAN JIKA KOSONG --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($menu->name) }}&background=fff7ed&color=ea580c&size=512"
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-extrabold text-gray-800 text-xl">{{ $menu->name }}</h3>
                            <span class="text-xs font-bold text-gray-400 uppercase">{{ $menu->category ?? 'Minuman' }}</span>
                        </div>
                        <p class="text-orange-600 font-black text-lg">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="mt-4">
                        @if($menu->stock > 0)
                            <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Tersedia</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="fixed bottom-6 left-0 right-0 px-6">
        <div class="max-w-md mx-auto bg-gray-900 text-white p-4 rounded-2xl shadow-2xl text-center">
            <span class="text-sm font-bold">Pesan di Kasir untuk Barcode ini</span>
        </div>
    </div>
</body>
</html>
