<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Sistem Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #print-area, #print-area * { visibility: visible !important; }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                background: white;
                color: black !important;
            }
            .page-break { page-break-after: always; display: block; height: 1px; }
        }
        .struk-font { font-family: 'Courier New', Courier, monospace; line-height: 1.2; }
    </style>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Riwayat Penjualan</h1>
                <p class="text-gray-500">Daftar seluruh transaksi yang berhasil dilakukan.</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('dashboard') }}" class="bg-gray-200 px-6 py-2 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">Dashboard</a>
                <a href="{{ route('pos') }}" class="bg-orange-500 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-orange-600 transition shadow-sm">
                    ← Kembali ke Kasir
                </a>
            </div>
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
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $transaction->created_at->format('d M Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $transaction->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $items = is_string($transaction->items) ? json_decode($transaction->items, true) : $transaction->items;
                                @endphp
                                @if(is_array($items))
                                    @foreach($items as $item)
                                        <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-md border border-orange-100">
                                            {{ $item['qty'] ?? '1' }}x {{ $item['name'] ?? 'Menu' }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button onclick='preparePrint({!! json_encode($transaction) !!})' class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded-lg transition border border-blue-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </button>
                            <button onclick="deleteTransaction({{ $transaction->id }})" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-lg transition border border-red-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada riwayat transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="print-area" class="struk-font" style="display:none;"></div>

    <script>
        function preparePrint(data) {
            const printArea = document.getElementById('print-area');
            printArea.style.display = 'block';

            const date = new Date(data.created_at).toLocaleString('id-ID', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            let itemsHtml = '';
            let dapurItemsHtml = '';
            const items = typeof data.items === 'string' ? JSON.parse(data.items) : data.items;

            if (Array.isArray(items)) {
                items.forEach(item => {
                    itemsHtml += `
                        <div style="display:flex; justify-content:space-between; margin-bottom: 2px; font-size: 12px;">
                            <span>${item.qty || 1}x ${item.name}</span>
                            <span>${( (item.qty || 1) * (item.price || 0)).toLocaleString('id-ID')}</span>
                        </div>`;

                    dapurItemsHtml += `
                        <div style="font-size: 16px; font-weight: bold; margin-bottom: 4px;">
                            ${item.qty || 1}x ${item.name}
                        </div>`;
                });
            }

            printArea.innerHTML = `
                <div style="width: 75mm; padding: 10px; color: black;">
                    <center>
                        <h2 style="margin:0; font-size: 18px;">CAFE PREMIUM</h2>
                        <p style="font-size:12px; margin:0;">Nota Pelanggan #${data.id}</p>
                        <p style="font-size:11px; margin:0;">${date}</p>
                    </center>
                    <hr style="border-top: 1px dashed black; margin: 8px 0;">
                    ${itemsHtml}
                    <hr style="border-top: 1px dashed black; margin: 8px 0;">
                    <div style="display:flex; justify-content:space-between; font-weight:bold; font-size: 13px;">
                        <span>TOTAL</span>
                        <span>Rp ${Number(data.total_price).toLocaleString('id-ID')}</span>
                    </div>
                    <center style="margin-top:10px; font-size:10px;">Terima Kasih!</center>

                    <div class="page-break" style="margin: 30px 0; border-top: 2px solid black;"></div>

                    <center>
                        <h2 style="margin:0; border: 2px solid black; padding: 3px; font-size: 18px;">ORDER DAPUR</h2>
                        <p style="font-size:12px; margin-top: 5px;">Nota #${data.id} | ${date}</p>
                    </center>
                    <hr style="border-top: 1px dashed black; margin: 8px 0;">
                    ${dapurItemsHtml}
                    <hr style="border-top: 1px dashed black; margin: 8px 0;">
                    <center><strong style="font-size: 12px;">--- SEGERA PROSES ---</strong></center>
                </div>
            `;

            setTimeout(() => {
                window.print();
                printArea.style.display = 'none';
            }, 500);
        }

        function deleteTransaction(id) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
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
                            Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
