<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kasir Café Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen">
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-orange-600 uppercase tracking-wider">Cafe Menu</h1>
                    <p class="text-gray-400 text-sm">Pilih menu untuk memulai pesanan</p>
                </div>
                <div class="flex gap-3">
                    <a href="/dashboard" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        📊 Dashboard
                    </a>
                    <a href="/history" class="bg-white border border-gray-300 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-100 transition shadow-sm text-gray-700">
                        📜 Riwayat
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white p-5 rounded-3xl shadow-sm hover:shadow-xl transition-all border border-gray-100 group">
                    <div class="flex justify-between items-start">
                        <span class="bg-orange-100 text-orange-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                            {{ $product->category->name }}
                        </span>

                        <span class="text-xs font-bold {{ $product->stock <= 5 ? 'text-red-600 animate-pulse' : 'text-gray-400' }}">
                            {!! $product->stock <= 5 ? '⚠️ Sisa: ' : 'Stok: ' !!} {{ $product->stock }}
                        </span>
                    </div>

                    <h2 class="text-xl font-bold mt-4 text-gray-800 group-hover:text-orange-500 transition-colors">{{ $product->name }}</h2>
                    <p class="text-2xl font-black text-gray-900 mt-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <button
                        onclick="addToCart('{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                        class="w-full mt-5 py-3 rounded-2xl font-bold text-sm transition-all active:scale-95 shadow-md
                        {{ $product->stock <= 0
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-orange-500 text-white hover:bg-orange-600 shadow-orange-100' }}"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'HABIS' : 'TAMBAH KE PESANAN' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="w-96 bg-white shadow-2xl flex flex-col border-l border-gray-100">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-2xl font-black text-gray-800 flex items-center">
                    <span class="mr-3 text-3xl">🛒</span> Pesanan
                </h2>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/50">
                <div class="flex flex-col items-center justify-center h-full opacity-20">
                    <span class="text-6xl mb-4">☕</span>
                    <p class="font-bold">Belum ada pesanan</p>
                </div>
            </div>

            <div class="p-8 bg-white border-t border-gray-100 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase text-xs tracking-widest">Subtotal</span>
                    <span id="subtotal" class="font-bold text-gray-800">Rp 0</span>
                </div>
                <div class="flex justify-between items-center pb-4">
                    <span class="text-gray-800 font-black text-lg">TOTAL</span>
                    <span id="total-price" class="text-3xl font-black text-orange-600">Rp 0</span>
                </div>

                <button onclick="checkout()" class="w-full bg-green-500 text-white py-5 rounded-2xl font-black text-lg hover:bg-green-600 shadow-xl shadow-green-100 transition-all hover:-translate-y-1 active:translate-y-0">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(name, price, maxStock) {
            const existingItem = cart.find(item => item.name === name);

            if (existingItem) {
                if (existingItem.qty >= maxStock) {
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Stok Terbatas!',
                        text: `Maaf, stok ${name} cuma ada ${maxStock} porsi.`,
                        confirmButtonColor: '#f97316'
                    });
                }
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
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full opacity-20">
                        <span class="text-6xl mb-4">☕</span>
                        <p class="font-bold uppercase tracking-widest text-xs">Keranjang Kosong</p>
                    </div>`;
                totalElement.innerText = 'Rp 0';
                subtotalElement.innerText = 'Rp 0';
                return;
            }

            container.innerHTML = cart.map((item, index) => `
                <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm animate-fadeIn">
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 text-sm leading-tight">${item.name}</h4>
                        <p class="text-xs text-gray-400 font-bold mt-1">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="text-right ml-4">
                        <p class="font-black text-orange-500 text-sm">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</p>
                        <button onclick="removeFromCart(${index})" class="text-[10px] text-red-400 hover:text-red-600 font-black uppercase mt-1 transition-colors">Hapus</button>
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
                <div style="font-family: 'Courier New', Courier, monospace; width: 280px; padding: 20px; color: #333;">
                    <div style="text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 10px; margin-bottom: 10px;">
                        <h2 style="margin:0; font-size: 18px;">CAFE LARAVEL</h2>
                        <p style="font-size:10px; margin: 5px 0;">Jl. Coding No. 123, Yogyakarta</p>
                        <p style="font-size:10px; margin: 0;">Nota: #${id}</p>
                    </div>
                    <div style="font-size:12px; line-height: 1.6;">
                        ${items.map(i => `
                            <div style="display: flex; justify-content: space-between;">
                                <span>${i.qty}x ${i.name.substring(0, 15)}</span>
                                <span>Rp ${(i.price * i.qty).toLocaleString()}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div style="border-top: 2px dashed #eee; margin-top: 10px; padding-top: 10px;">
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px;">
                            <span>TOTAL</span>
                            <span>Rp ${total.toLocaleString()}</span>
                        </div>
                    </div>
                    <div style="text-align:center; margin-top:20px; font-size:10px; border-top: 1px solid #eee; padding-top: 10px;">
                        *** TERIMA KASIH ***<br>Silakan Datang Kembali
                    </div>
                </div>
            `;
            let win = window.open('', '', 'height=600,width=400');
            win.document.write('<html><body style="margin:0; display:flex; justify-content:center;">' + content + '</body></html>');
            win.document.close();
            win.print();
        }

        function checkout() {
            if (cart.length === 0) return Swal.fire('Oops!', 'Keranjang masih kosong nih.', 'error');

            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            Swal.fire({
                title: 'Selesaikan Pembayaran?',
                text: "Total Tagihan: Rp " + total.toLocaleString('id-ID'),
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Bayar Sekarang!'
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                printReceipt(data.transaction_id, total, cart);
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Transaksi Gagal', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Sistem Error', 'Gagal menghubungi server.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>
