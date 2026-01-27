<x-app-layout>
    <div class="container-fluid p-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: linear-gradient(45deg, #1e293b, #334155);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-1 text-white">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-white-50 mb-0">
                            Role: <span class="badge bg-orange px-3" style="background-color: #ea580c;">{{ strtoupper(Auth::user()->role) }}</span> | Shift: <b>{{ Auth::user()->shift }}</b>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <h2 class="fw-bold mb-0 text-white" id="live-clock">00:00:00</h2>
                        <small class="text-white-50">{{ date('d F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 p-4 shadow-sm h-100 card-hover" style="border-radius: 20px; border-left: 5px solid #0ea5e9 !important;">
                    <p class="text-muted small fw-bold text-uppercase mb-1 text-dark">Total Menu</p>
                    <h2 class="fw-extrabold mb-0 text-dark">{{ $totalMenu }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 shadow-sm h-100 card-hover" style="border-radius: 20px; border-left: 5px solid #10b981 !important;">
                    <p class="text-muted small fw-bold text-uppercase mb-1 text-dark">Transaksi Hari Ini</p>
                    <h2 class="fw-extrabold mb-0 text-dark">{{ $transaksiHariIni }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 p-4 shadow-sm h-100 card-hover" style="border-radius: 20px; border-left: 5px solid #ea580c !important;">
                    <p class="text-muted small fw-bold text-uppercase mb-1 text-dark">Omzet Hari Ini</p>
                    <h2 class="fw-extrabold mb-0" style="color: #ea580c;">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 p-4 shadow-sm" style="border-radius: 20px;">
                    <h6 class="fw-bold mb-4">📈 Tren Penjualan (7 Hari Terakhir)</h6>
                    <div style="height: 320px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 p-4 shadow-sm h-100" style="border-radius: 20px;">
                    <h6 class="fw-bold mb-4">🔥 5 Menu Terlaris</h6>
                    @forelse($bestSeller as $name => $qty)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-4" style="background: #f8fafc;">
                        <span class="small fw-bold text-secondary">{{ strtoupper($name) }}</span>
                        <span class="badge rounded-pill px-3 py-2" style="background-color: #fff7ed; color: #ea580c; border: 1px solid #ffedd5;">
                            {{ $qty }} TERJUAL
                        </span>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12"><h6 class="fw-bold mb-2 text-dark">🕒 Rekap Pendapatan per Shift (Hari Ini)</h6></div>
            @php
                $recapShifts = [
                    ['label' => 'Shift Pagi', 'val' => $shiftPagi, 'icon' => '☀️'],
                    ['label' => 'Shift Siang', 'val' => $shiftSiang, 'icon' => '🌤️'],
                    ['label' => 'Shift Malam', 'val' => $shiftMalam, 'icon' => '🌙']
                ];
            @endphp
            @foreach($recapShifts as $s)
            <div class="col-md-4">
                <div class="p-3 rounded-4 bg-white shadow-sm d-flex align-items-center card-hover border-0">
                    <div class="me-3 fs-3">{{ $s['icon'] }}</div>
                    <div>
                        <small class="text-muted d-block">{{ $s['label'] }}</small>
                        <b class="fs-5 text-dark">Rp {{ number_format($s['val'], 0, ',', '.') }}</b>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                const now = new Date();
                document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID');
            }, 1000);

            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($totals) !!},
                        borderColor: '#ea580c',
                        backgroundColor: 'rgba(234, 88, 12, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    <style>
        .fw-extrabold { font-weight: 800; }
        .card-hover:hover { transform: translateY(-3px); transition: 0.3s; box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important; }
    </style>
</x-app-layout>
