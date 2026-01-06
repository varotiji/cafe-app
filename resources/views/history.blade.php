<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Sistem Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-5xl mx-auto">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">Aksi</th>
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
                        <td class="px-6 py-4 text-center">
                            <button onclick="deleteTransaction({{ $transaction->id }})" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-lg transition border border-red-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
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
                        <td class="bg-orange-100"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6 italic">Data ini diperbarui secara real-time dari database.</p>
    </div>

    <script>
        function deleteTransaction(id) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data akan dihapus dan STOK AKAN DIKEMBALIKAN!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus & Refund!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/transaction/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Terhapus!', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                    });
                }
            })
        }
    </script>
</body>
</html>
