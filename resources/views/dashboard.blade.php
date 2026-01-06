<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cafe Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight">DASHBOARD ANALITIK</h1>
                <p class="text-slate-500 font-medium">Pantau performa cafe kamu secara real-time.</p>
            </div>
            <div class="flex gap-3">
                <a href="/history" class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-slate-50 transition">Riwayat</a>
                <a href="/" class="bg-orange-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-600 transition">Ke Kasir</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl text-green-500">💰</div>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-2">Total Omzet</p>
                <h2 class="text-4xl font-black text-slate-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl text-blue-500">📑</div>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-2">Total Pesanan</p>
                <h2 class="text-4xl font-black text-slate-800">{{ $totalTransaksi }} <span class="text-lg font-medium text-slate-400">Transaksi</span></h2>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center">
                <span class="mr-3">🔥</span> Produk Paling Laku (Best Seller)
            </h3>
            <div class="space-y-4">
                @foreach($bestSeller as $nama => $jumlah)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-orange-200 transition group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center font-bold mr-4 group-hover:bg-orange-500 group-hover:text-white transition">
                            {{ $loop->iteration }}
                        </div>
                        <span class="font-bold text-slate-700">{{ $nama }}</span>
                    </div>
                    <span class="bg-white px-4 py-1.5 rounded-full border border-slate-200 text-sm font-bold text-slate-500">
                        {{ $jumlah }} Terjual
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
