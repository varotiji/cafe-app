<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Cafe Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <div class="bg-orange-600 p-6 text-white rounded-b-3xl shadow-lg sticky top-0 z-50">
        <h1 class="text-2xl font-bold">Cafe Premium ☕</h1>
        <p class="text-sm opacity-90">Pilih menu favoritmu di bawah ini</p>
    </div>

    <div class="p-4 space-y-4 pb-40">
        @foreach($products as $product)
        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center border border-gray-200">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-xl shadow-inner">

            <div class="ml-4 flex-1">
                <h3 class="font-bold text-gray-800 text-lg">{{ $product->name }}</h3>
                <p class="text-gray-500 text-xs mb-2">{{ Str::limit($product->description, 50) }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-orange-600 font-bold text-md">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                    <div class="flex items-center bg-gray-100 rounded-full p-1">
                        <button onclick="changeQty({{ $product->id }}, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-sm font-bold text-orange-600 hover:bg-orange-100">-</button>

                        <input type="number" id="qty-{{ $product->id }}" value="0" min="0"
                               class="w-10 text-center bg-transparent font-bold text-gray-800 pointer-events-none" readonly>

                        <button onclick="changeQty({{ $product->id }}, 1)" class="w-8 h-8 flex items-center justify-center bg-orange-600 rounded-full shadow-sm font-bold text-white hover:bg-orange-700">+</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="fixed bottom-0 w-full bg-white border-t rounded-t-3xl p-6 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-50">
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-400 font-medium text-sm uppercase tracking-wider">Total Pembayaran</span>

<div class="mb-3">
    <label for="note" class="form-label small fw-bold text-muted">CATATAN PESANAN (OPSIONAL)</label>
    <textarea name="note" id="note" class="form-control border-0 bg-light" rows="2"
              placeholder="Contoh: Kurangi gula, bungkus terpisah, dll..."
              style="border-radius: 12px; resize: none;"></textarea>
</div>

            <span id="total-display" class="text-2xl font-black text-orange-600 tracking-tight">Rp 0</span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button onclick="payCash()" class="bg-gray-100 text-gray-800 py-4 rounded-2xl font-bold text-sm border border-gray-200 active:scale-95 transition-all">
                💵 Bayar Cash
            </button>

            <button id="pay-button" class="bg-orange-600 text-white py-4 rounded-2xl font-bold text-sm shadow-lg shadow-orange-200 active:scale-95 transition-all">
                📱 Bayar QRIS
            </button>
        </div>
    </div>

<script>
    let cart = {};
    const products = @json($products);

    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let currentVal = parseInt(input.value) || 0;
        let newVal = currentVal + delta;
        if (newVal >= 0) {
            input.value = newVal;
            if (newVal > 0) { cart[id] = newVal; } else { delete cart[id]; }
            updateTotal();
        }
    }

    function updateTotal() {
        let total = 0;
        for (let id in cart) {
            let product = products.find(p => p.id == id);
            if(product) total += product.price * cart[id];
        }
        document.getElementById('total-display').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function getCartItems() {
        return Object.keys(cart).map(id => {
            let p = products.find(prod => prod.id == id);
            return {
                name: p.name,
                qty: cart[id],
                price: p.price
            };
        });
    }

    function payCash() {
        if (Object.keys(cart).length === 0) return alert('Pilih menu dulu!');

        let total = 0;
        for (let id in cart) {
            let product = products.find(p => p.id == id);
            total += product.price * cart[id];
        }

        fetch('{{ route("checkout.cash") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                total_price: total,
                items: getCartItems()
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Pesanan dikirim! Silakan bayar ke kasir.');
                // SUDAH DIGANTI KE /history BIAR GAK 404
                window.location.href = '/history';
            } else {
                alert('Gagal: ' + (data.error || 'Terjadi kesalahan sistem'));
            }
        })
        .catch(err => alert('Gagal koneksi ke server.'));
    }

    document.getElementById('pay-button').onclick = function() {
        if (Object.keys(cart).length === 0) return alert('Pilih menu dulu!');

        let total = 0;
        for (let id in cart) {
            let product = products.find(p => p.id == id);
            total += product.price * cart[id];
        }

        fetch('{{ route("checkout.customer") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                total_price: total,
                items: getCartItems()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result){ window.location.href = '/history'; },
                    onPending: function(result){ window.location.href = '/history'; },
                    onError: function(result){ alert("Pembayaran gagal!"); }
                });
            } else {
                alert('Gagal: ' + (data.error || 'Server Key belum terbaca'));
            }
        });
    };
</script>
</body>
</html>
