<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Sistem Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Riwayat Penjualan</h1>
                <p class="text-gray-500">Daftar seluruh transaksi yang berhasil dilakukan.</p>
            </div>
            <a href="/" class="bg-white border border-gray-300 px-6 py-2 rounded-xl text-sm font-semibold hover:bg-gray-100 transition shadow-sm">
                ← Kembali ke Kasir
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ID Transaksi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Detail Menu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $transaction->created_at->format('d M Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $transaction->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($transaction->items as $item)
                                <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-md border border-orange-100">
                                    {{ $item['qty'] }}x {{ $item['name'] }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-orange-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 font-bold text-orange-800 text-right uppercase tracking-wider">
                            Total Pendapatan Keseluruhan
                        </td>
                        <td class="px-6 py-4 text-right text-xl font-black text-orange-600">
                            Rp {{ number_format($transactions->sum('total_price'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6 italic">Data ini diperbarui secara real-time dari database.</p>
    </div>
</body>
</html>
