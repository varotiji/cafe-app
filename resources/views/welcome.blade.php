<x-app-layout>
    <div class="flex h-screen bg-gray-50 overflow-hidden font-sans">
        <div class="flex-1 p-8 overflow-y-auto h-full">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-black text-orange-600 tracking-tighter uppercase">Cafe Menu</h1>
                    <p class="text-gray-400 text-sm font-medium italic underline decoration-orange-300">Pilih menu pesanan pelanggan di bawah ini</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="filterCategory('all', this)" class="cat-btn bg-orange-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md">Semua Menu</button>
                <button onclick="filterCategory('🍚 Makanan Berat', this)" class="cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all">🍚 Makanan</button>
                <button onclick="filterCategory('🍜 Mie & Bakso', this)" class="cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all">🍜 Mie & Bakso</button>
                <button onclick="filterCategory('🍢 Snack & Camilan', this)" class="cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all">🍢 Snack</button>
                <button onclick="filterCategory('🍔 Western Food', this)" class="cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all">🍔 Western</button>
                <button onclick="filterCategory('🧁 Dessert & Minum', this)" class="cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all">🧁 Dessert</button>
            </div>

            <div class="mb-8 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 text-xl">🔍</span>
                <input type="text" id="searchMenu" onkeyup="filterMenu()" placeholder="Cari pesanan pelanggan..."
                class="w-full pl-12 pr-5 py-4 rounded-2xl border-none shadow-sm focus:ring-4 focus:ring-orange-100 outline-none transition bg-white text-gray-700 font-semibold text-lg">
            </div>

            <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-24">
                @foreach($products as $product)
                <div class="product-card bg-white p-5 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all border border-gray-100 group animate-fadeIn" data-category="{{ $product->category->name }}">
                    <div class="flex justify-between items-start">
                        <span class="bg-orange-50 text-orange-600 text-[10px] font-black px-3 py-1 rounded-lg uppercase category-label">
                            {{ $product->category->name }}
                        </span>
                        <span class="text-[11px] font-bold {{ $product->stock <= 5 ? 'text-red-600 animate-pulse' : 'text-gray-400' }}">
                            {!! $product->stock <= 5 ? '⚠️ Sisa: ' : 'Stok: ' !!} {{ $product->stock }}
                        </span>
                    </div>

                    <h2 class="product-name text-lg font-bold mt-4 text-gray-800 group-hover:text-orange-600 transition-colors leading-tight h-12">{{ $product->name }}</h2>
                    <p class="text-xl font-black text-gray-900 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <button
                        onclick="addToCart('{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                        class="w-full mt-4 py-4 rounded-2xl font-black text-xs transition-all active:scale-95 shadow-md
                        {{ $product->stock <= 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-orange-500 text-white hover:bg-orange-600 shadow-orange-100' }}"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'STOK HABIS' : 'TAMBAH PESANAN' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="w-[450px] bg-white shadow-2xl flex flex-col border-l border-gray-100">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <h2 class="text-2xl font-black text-gray-800">🛒 Daftar Pesanan</h2>
                <button onclick="clearCart()" class="text-[10px] text-red-500 font-black hover:bg-red-50 px-4 py-2 rounded-xl transition uppercase border border-red-100">Reset</button>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/30">
                <div class="flex flex-col items-center justify-center h-full opacity-30 text-center">
                    <span class="text-7xl mb-4">🍽️</span>
                    <p class="font-bold uppercase tracking-widest text-[11px]">Belum Ada Pesanan Terpilih</p>
                </div>
            </div>

            <div class="p-8 bg-white border-t border-gray-100 space-y-4 shadow-[0_-15px_30px_rgba(0,0,0,0.03)] pb-10">
                <div class="flex justify-between items-center text-gray-400 font-bold text-[10px] tracking-widest uppercase">
                    <span>Subtotal Tagihan</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between items-center pb-4">
                    <span class="text-gray-800 font-black text-xl">TOTAL AKHIR</span>
                    <span id="total-price" class="text-4xl font-black text-orange-600 tracking-tighter">Rp 0</span>
                </div>

                <button onclick="checkout()" class="w-full bg-green-500 text-white py-6 rounded-3xl font-black text-xl hover:bg-green-600 shadow-2xl shadow-green-200 transition-all hover:-translate-y-1 active:translate-y-0">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let cart = [];
        let currentCategory = 'all';

        // --- FUNGSI PRINT STRUK ---
        function printReceipt(transactionId, items, totalPrice, cash, change) {
            let receiptContent = `
                <div style="font-family: 'Courier New', Courier, monospace; width: 300px; padding: 20px; color: #000;">
                    <div style="text-align: center;">
                        <h2 style="margin: 0;">CAFE POS</h2>
                        <p style="font-size: 12px;">Jl. Raya Pendidikan No. 10</p>
                        <p style="font-size: 12px;">---------------------------</p>
                    </div>
                    <div style="font-size: 12px; margin-bottom: 10px;">
                        <span>ID: #${transactionId}</span><br>
                        <span>Tgl: ${new Date().toLocaleString('id-ID')}</span>
                    </div>
                    <p style="font-size: 12px;">---------------------------</p>
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                        ${items.map(item => `
                            <tr>
                                <td style="padding: 2px 0;">${item.name}</td>
                                <td style="text-align: right;">${item.qty}x</td>
                                <td style="text-align: right;">${(item.price * item.qty).toLocaleString('id-ID')}</td>
                            </tr>
                        `).join('')}
                    </table>
                    <p style="font-size: 12px;">---------------------------</p>
                    <div style="font-size: 12px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>TOTAL:</span>
                            <span style="font-weight: bold;">Rp ${totalPrice.toLocaleString('id-ID')}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>TUNAI:</span>
                            <span>Rp ${parseInt(cash).toLocaleString('id-ID')}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>KEMBALI:</span>
                            <span>Rp ${change.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    <p style="font-size: 12px;">---------------------------</p>
                    <div style="text-align: center; font-size: 10px;">
                        <p>Terima kasih telah berkunjung!</p>
                        <p>Barang yang sudah dibeli tidak dapat ditukar.</p>
                    </div>
                </div>
            `;

            let printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Print Struk</title></head><body>');
            printWindow.document.write(receiptContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Tunggu gambar/konten load lalu print
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        // --- FUNGSI KASIR ---
        function filterCategory(category, btn) {
            currentCategory = category;
            document.querySelectorAll('.cat-btn').forEach(b => {
                b.className = "cat-btn bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-orange-50 transition-all";
            });
            btn.className = "cat-btn bg-orange-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md";
            runFilter();
        }

        function filterMenu() { runFilter(); }

        function runFilter() {
            let searchInput = document.getElementById('searchMenu').value.toLowerCase();
            let cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                let name = card.querySelector('.product-name').innerText.toLowerCase();
                let category = card.getAttribute('data-category');
                let matchSearch = name.includes(searchInput);
                let matchCategory = (currentCategory === 'all' || category === currentCategory);
                card.style.display = (matchSearch && matchCategory) ? "" : "none";
            });
        }

        function addToCart(name, price, maxStock) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                if (existingItem.qty >= maxStock) {
                    return Swal.fire('Stok Terbatas', `Maaf, stok hanya tersedia ${maxStock}`, 'warning');
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
                container.innerHTML = `<div class="flex flex-col items-center justify-center h-full opacity-20 text-center"><span class="text-6xl mb-4">☕</span><p class="font-bold uppercase tracking-widest text-[10px]">Keranjang Kosong</p></div>`;
                totalElement.innerText = 'Rp 0';
                subtotalElement.innerText = 'Rp 0';
                return;
            }

            container.innerHTML = cart.map((item, index) => `
                <div class="flex justify-between items-center bg-white p-5 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 text-sm">${item.name}</h4>
                        <p class="text-[11px] text-gray-400 font-bold mt-1">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-orange-500 text-sm">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</p>
                        <button onclick="removeFromCart(${index})" class="text-[9px] text-red-400 hover:text-red-600 font-bold uppercase">Hapus</button>
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

        function clearCart() {
            cart = [];
            renderCart();
        }

        function checkout() {
            if (cart.length === 0) return;
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            Swal.fire({
                title: 'Konfirmasi Bayar',
                html: `<p class="text-3xl font-black text-orange-600">Rp ${total.toLocaleString('id-ID')}</p><br><input type="number" id="cash-amount" class="swal2-input" placeholder="Masukkan Uang Tunai">`,
                showCancelButton: true,
                confirmButtonText: 'Proses & Cetak Struk',
                preConfirm: () => {
                    const cash = document.getElementById('cash-amount').value;
                    if (!cash || parseInt(cash) < total) {
                        Swal.showValidationMessage(`Uang kurang! Total tagihan: Rp ${total.toLocaleString('id-ID')}`);
                    }
                    return { cash: cash };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const cash = result.value.cash;
                    const change = cash - total;

                    fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ total_price: total, items: cart })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // --- EKSEKUSI PRINT SETELAH SAVE DATABASE BERHASIL ---
                            printReceipt(data.transaction_id, cart, total, cash, change);

                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi Berhasil!',
                                html: `Kembalian: <b class="text-2xl text-green-600">Rp ${change.toLocaleString('id-ID')}</b>`,
                                confirmButtonText: 'Selesai'
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error!', 'Terjadi gangguan koneksi.', 'error');
                    });
                }
            });
        }
    </script>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #ea580c; border-radius: 10px; }
        .product-card { transition: all 0.3s ease; }
    </style>
</x-app-layout>
