<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk_{{ $transaction->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.2; width: 300px; margin: auto; padding: 10px; color: #000; }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; }
        .footer { margin-top: 15px; font-size: 10px; }
        .ticket-dapur { margin-top: 50px; border: 2px solid #000; padding: 10px; page-break-before: always; }
        .no-print { margin-bottom: 20px; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { width: 100%; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px; background: #000; color: #fff; border: none; cursor: pointer;">CETAK SEMUA STRUK</button>
        <a href="{{ route('history') }}" style="display: inline-block; padding: 10px; background: #ccc; text-decoration: none; color: #000;">KEMBALI</a>
    </div>

    <div class="struk-pelanggan">
        <div class="text-center">
            <h3 style="margin: 0;">CAFE PREMIUM</h3>
            <p style="margin: 0;">Bukti Pembayaran</p>
        </div>
        <div class="divider"></div>
        <div class="flex"><span>No: #{{ $transaction->id }}</span> <span>{{ $transaction->created_at->format('d/m/y H:i') }}</span></div>
        <div class="flex"><span>Tipe: {{ $transaction->order_type }}</span></div>
        <div class="divider"></div>

        @foreach($transaction->details as $detail)
        <div style="margin-bottom: 5px;">
            <div style="font-weight: bold;">{{ $detail->menu->name }}</div>
            <div class="flex">
                <span>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="divider"></div>
        <div class="flex" style="font-weight: bold;"><span>TOTAL</span> <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></div>
        <div class="text-center" style="margin-top: 10px;">
            <p style="margin: 0;">Metode: {{ $transaction->payment_method }}</p>
            <div style="margin-top: 5px;">{!! $qrCode !!}</div>
            <p style="font-size: 10px;">{{ $statusLabel }}</p>
        </div>
        <div class="footer text-center">Terima Kasih!</div>
    </div>

    <div class="ticket-dapur">
        <div class="text-center">
            <h2 style="margin: 0;">STRUK DAPUR</h2>
            <p style="margin: 0; font-size: 14px;">Order ID: #{{ $transaction->id }}</p>
            <p style="margin: 0;">{{ $transaction->created_at->format('H:i:s') }}</p>
        </div>
        <div class="divider"></div>
        <div style="font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 10px;">
            {{ strtoupper($transaction->order_type) }}
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            @foreach($transaction->details as $detail)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="font-size: 18px; font-weight: bold; padding: 5px 0;">[ {{ $detail->quantity }} ]</td>
                <td style="font-size: 18px; padding: 5px 0;">{{ $detail->menu->name }}</td>
            </tr>
            @endforeach
        </table>

        @if($transaction->note)
        <div style="margin-top: 10px; padding: 5px; border: 1px solid #000;">
            <strong>CATATAN:</strong><br>
            {{ $transaction->note }}
        </div>
        @endif

        <div class="divider"></div>
        <div class="text-center" style="font-size: 10px;">
            Selesaikan pesanan segera!
        </div>
    </div>

</body>
</html>
