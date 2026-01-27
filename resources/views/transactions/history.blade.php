<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">🕒 History Penjualan</h2>
                <p class="text-muted">Daftar semua transaksi yang berhasil diproses.</p>
            </div>
            <button onclick="window.location.reload()" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-arrow-clockwise"></i> Refresh Data
            </button>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID TRANS</th>
                            <th>TANGGAL</th>
                            <th>MENU DI BELI</th>
                            <th>TOTAL BAYAR</th>
                            <th>METODE</th>
                            <th class="text-center">STRUK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trans)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#{{ $trans->id }}</td>
                            <td>{{ $trans->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($trans->details as $detail)
                                        <li>• {{ $detail->menu->name ?? 'Menu Dihapus' }} (x{{ $detail->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($trans->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $trans->payment_method == 'Tunai' ? 'bg-success' : 'bg-info text-dark' }}">
                                    {{ $trans->payment_method }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('transactions.print', $trans->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="bi bi-printer text-dark"></i> Print
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Belum ada transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
