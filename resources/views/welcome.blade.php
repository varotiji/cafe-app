<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kasir Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen">
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-orange-600 uppercase tracking-wider">Cafe Menu</h1>
                <a href="/history" class="bg-white border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-100 transition shadow-sm">
                    📜 Riwayat Penjualan
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                    <div class="flex justify-between items-start">
                        <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                            {{ $product->category->name }}
                        </span>
                        <span class="text-gray-400 text-xs">Stok: {{ $product->stock }}</span>
                    </div>
                    <h2 class="text-lg font-bold mt-3 text-gray-800">{{ $product->name }}</h2>
                    <p class="text-orange-500 font-bold text-xl mt-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <button
                        onclick="addToCart('{{ $product->name }}', {{ $product->price }})"
                        class="w-full mt-4 bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 active:scale-95 transition-all">
                        Tambah Pesanan
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="w-96 bg-white shadow-2xl flex flex-col border-l border-gray-200">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="mr-2">🛒</span> Pesanan
                </h2>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4">
                <p class="text-gray-400 text-center mt-10">Belum ada pesanan</p>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Subtotal</span>
                    <span id="subtotal" class="font-semibold">Rp 0</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-800 mb-6">
                    <span>Total</span>
                    <span id="total-price" class="text-orange-600">Rp 0</span>
                </div>
                <button onclick="checkout()" class="w-full bg-green-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-green-600 shadow-lg shadow-green-100 transition-all active:scale-95">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(name, price) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ name, price, qty: 1 });
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const totalElement = document.getElementById('total-price');
            const subtotalElement = document.getElementById('subtotal');

            if (cart.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center mt-10">Belum ada pesanan</p>';
                totalElement.innerText = 'Rp 0';
                subtotalElement.innerText = 'Rp 0';
                return;
            }

            container.innerHTML = cart.map((item, index) => `
                <div class="flex justify-between items-center bg-white border border-gray-100 p-3 rounded-xl shadow-sm">
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">${item.name}</h4>
                        <p class="text-xs text-gray-400">${item.qty}x Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-orange-500 text-sm">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</p>
                        <button onclick="removeFromCart(${index})" class="text-[10px] text-red-400 hover:text-red-600 uppercase font-bold">Hapus</button>
                    </div>
                </div>
            `).join('');

            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            totalElement.innerText = `Rp ${total.toLocaleString('id-ID')}`;
            subtotalElement.innerText = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function printReceipt(id, total, items) {
            let content = `
                <div style="font-family: monospace; width: 250px; padding: 10px;">
                    <div style="text-align: center;">
                        <h3 style="margin:0">CAFE LARAVEL</h3>
                        <p style="font-size:10px">Nota: #${id}</p>
                        <hr>
                    </div>
                    <div style="font-size:12px">
                        ${items.map(i => `<div>${i.qty} ${i.name} <span style="float:right">Rp ${(i.price * i.qty).toLocaleString()}</span></div>`).join('')}
                    </div>
                    <hr>
                    <div style="font-weight:bold">TOTAL <span style="float:right">Rp ${total.toLocaleString()}</span></div>
                    <div style="text-align:center; margin-top:15px; font-size:10px">Terima Kasih!</div>
                </div>
            `;
            let win = window.open('', '', 'height=500,width=300');
            win.document.write(content);
            win.document.close();
            win.print();
        }

        function checkout() {
            if (cart.length === 0) return Swal.fire('Oops!', 'Keranjang kosong', 'error');

            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            Swal.fire({
                title: 'Konfirmasi Bayar?',
                text: "Total: Rp " + total.toLocaleString('id-ID'),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bayar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ total_price: total, items: cart })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                printReceipt(data.transaction_id, total, cart);
                                cart = [];
                                renderCart();
                                window.location.href = '/history';
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
