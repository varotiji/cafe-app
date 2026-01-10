<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - #{{ $transaction->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; padding: 10px; margin: auto; }
        .text-center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; font-size: 12px; }
        .info { font-size: 12px; margin-bottom: 5px; }
        .total { font-size: 16px; margin-top: 10px; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h2 style="margin-bottom: 5px;">CAFE MANTAP</h2>
        <p style="font-size: 10px;">Jl. Kopi Nikmat No. 1, Jakarta</p>
    </div>

    <div class="line"></div>

    <div class="info">
        No: #TRX-{{ $transaction->id }}<br>
        Tgl: {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        Tipe: <strong>{{ strtoupper($transaction->order_type) }}</strong><br>
        Bayar: <strong>{{ strtoupper($transaction->payment_method) }}</strong>
    </div>

    <div class="line"></div>

    <table>
        @foreach($transaction->details as $detail)
        <tr>
            <td>{{ $detail->menu->name }}</td>
            <td align="center">{{ $detail->quantity }}x</td>
            <td align="right">{{ number_format($detail->price, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <div class="total">
        <strong>TOTAL: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
    </div>

    <div class="line"></div>

    <p class="text-center" style="font-size: 11px;">
        Terima Kasih Atas Kunjungannya<br>
        <strong>KAMI TUNGGU KEMBALI!</strong>
    </p>

    <script>
        // Dialog print otomatis
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
