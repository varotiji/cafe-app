<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📊 Dashboard Analisis Kafe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-8 border-blue-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Menu</p>
                    <p class="text-3xl font-black text-gray-800">{{ $totalMenu }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-8 border-green-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Transaksi Hari Ini</p>
                    <p class="text-3xl font-black text-gray-800">{{ $transaksiHariIni }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-8 border-orange-500">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Omzet Hari Ini</p>
                    <p class="text-3xl font-black text-gray-800">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-md">
                    <h3 class="font-bold text-gray-700 mb-4">📈 Tren Penjualan 7 Hari Terakhir</h3>
                    <canvas id="myChart" height="150"></canvas>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-orange-500">
                    <h3 class="font-bold text-gray-700 mb-4 italic underline">🔥 5 Menu Terlaris</h3>
                    <div class="space-y-3">
                        @foreach($bestSeller as $name => $qty)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                            <span class="text-sm font-bold text-gray-600">{{ $name }}</span>
                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-[10px] font-black">
                                {{ $qty }} Sold
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-4 px-2">🕒 Pendapatan Per Shift</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <p class="text-xs font-bold text-orange-600 uppercase">Pagi (06:00 - 15:00)</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($shiftPagi, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-xs font-bold text-blue-600 uppercase">Siang (15:00 - 19:00)</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($shiftSiang, 0, ',', '.') }}</p>
                </div>
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                    <p class="text-xs font-bold text-indigo-600 uppercase">Malam (19:00 - 05:00)</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($shiftMalam, 0, ',', '.') }}</p>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: {!! json_encode($totals) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            }
        });
    </script>
</x-app-layout>
