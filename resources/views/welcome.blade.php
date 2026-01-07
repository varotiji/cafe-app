<x-app-layout>
    <div class="flex h-screen bg-[#FFFBF0] overflow-hidden font-sans text-gray-800">

        <div class="flex-1 p-8 overflow-y-auto h-full custom-scrollbar">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase leading-none">
                        PREMIUM <span class="text-orange-600">CAFE</span>
                    </h1>
                    <div class="h-1.5 w-24 bg-orange-500 mt-2 rounded-full"></div>
                </div>
                <div class="bg-orange-100 text-orange-700 px-5 py-2 rounded-2xl shadow-sm border border-orange-200 font-bold text-sm">
                    📅 {{ date('D, d M Y') }}
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mb-8">
                <button onclick="filterCategory('all', this)" class="cat-btn bg-orange-600 text-white px-8 py-3 rounded-2xl text-xs font-black transition-all shadow-lg uppercase tracking-wider">🌟 Semua</button>
                <button onclick="filterCategory('🍚 Makanan Berat', this)" class="cat-btn bg-white text-gray-500 border border-orange-100 px-6 py-3 rounded-2xl text-xs font-black transition-all hover:bg-orange-50 uppercase tracking-wider">🍚 Makanan</button>
                <button onclick="filterCategory('🍜 Mie & Bakso', this)" class="cat-btn bg-white text-gray-500 border border-orange-100 px-6 py-3 rounded-2xl text-xs font-black transition-all hover:bg-orange-50 uppercase tracking-wider">🍜 Mie</button>
                <button onclick="filterCategory('🍢 Snack & Camilan', this)" class="cat-btn bg-white text-gray-500 border border-orange-100 px-6 py-3 rounded-2xl text-xs font-black transition-all hover:bg-orange-50 uppercase tracking-wider">🍢 Snack</button>
                <button onclick="filterCategory('🧁 Dessert & Minum', this)" class="cat-btn bg-white text-gray-500 border border-orange-100 px-6 py-3 rounded-2xl text-xs font-black transition-all hover:bg-orange-50 uppercase tracking-wider">☕ Minuman</button>
            </div>

            <div class="mb-10 relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-orange-400 text-xl">🔍</span>
                <input type="text" id="searchMenu" onkeyup="filterMenu()" placeholder="Cari menu favorit..."
                class="w-full pl-14 pr-5 py-5 rounded-2xl border-2 border-orange-50 shadow-md focus:border-orange-400 focus:ring-0 outline-none transition bg-white text-gray-800 font-bold text-lg placeholder-gray-300">
            </div>

            <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-32">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-[2rem] shadow-md hover:shadow-orange-200 hover:-translate-y-2 transition-all border border-orange-50 overflow-hidden group relative flex flex-col" data-category="{{ $product->category->name }}">

                    <div class="relative h-44 w-full overflow-hidden bg-gray-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 font-bold text-xs uppercase italic">No Image</div>
                        @endif

                        <div class="absolute top-4 right-4">
                            <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-sm
                                @if($product->stock <= 0) bg-gray-200 text-gray-500
                                @elseif($product->stock <= 5) bg-red-500 text-white animate-pulse
                                @else bg-white/90 text-green-600 backdrop-blur-md @endif">
                                @if($product->stock <= 0) Habis
                                @elseif($product->stock <= 5) ⚠️ Sisa {{ $product->stock }}
                                @else Ready: {{ $product->stock }} @endif
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <span class="text-orange-500 text-[9px] font-black uppercase tracking-widest bg-orange-50 px-2 py-1 rounded-md self-start">{{ $product->category->name }}</span>

                        <h2 class="product-name text-lg font-black text-gray-800 leading-tight mt-2 uppercase tracking-tight">{{ $product->name }}</h2>

                        <p class="text-gray-400 text-[11px] leading-relaxed mt-1 line-clamp-2 italic min-h-[32px]">
                            {{ $product->description ?? 'Nikmati menu spesial kami dengan cita rasa autentik.' }}
                        </p>

                        <div class="flex justify-between items-center mt-auto pt-4">
                            <p class="text-xl font-black text-orange-600 tracking-tighter">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <button
                                onclick="addToCart('{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                                class="p-3 rounded-xl transition-all shadow-lg active:scale-90
                                {{ $product->stock <= 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-900 text-white hover:bg-orange-500' }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="w-[450px] bg-white shadow-2xl flex flex-col z-10 border-l border-orange-100">
            <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">🛒 Pesanan</h2>
                    <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest mt-1 italic">Order List</p>
                </div>
                <button onclick="clearCart()" class="text-[10px] text-red-500 font-black hover:bg-red-50 px-4 py-2 rounded-xl transition uppercase">Reset</button>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4 bg-orange-50/30">
                <div class="flex flex-col items-center justify-center h-full opacity-20 text-center">
                    <span class="text-6xl mb-4">☕</span>
                    <p class="font-bold uppercase text-[10px]">Keranjang Kosong</p>
                </div>
            </div>

            <div class="p-8 bg-white border-t border-gray-100 space-y-6 shadow-[0_-20px_40px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold text-xs uppercase tracking-widest">Total Bayar</span>
                    <span id="total-price" class="text-3xl font-black text-orange-600 tracking-tighter">Rp 0</span>
                </div>

                <button onclick="checkout()" class="w-full bg-orange-500 text-white py-6 rounded-3xl font-black text-xl hover:bg-orange-600 shadow-xl shadow-orange-200 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                    <span>💳</span> BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let cart = [];
        let currentCategory = 'all';

        function addToCart(name, price, maxStock) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                if (existingItem.qty >= maxStock) {
                    return Swal.fire({ icon: 'warning', title: 'Stok Terbatas', text: `Stok tersedia tinggal ${maxStock}`, confirmButtonColor: '#f97316'});
                }
                existingItem.qty += 1;
            } else {
                cart.push({ name, price, qty: 1, note: '' });
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            const totalElement = document.getElementById('total-price');

            if (cart.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center h-full opacity-20 text-center"><span class="text-6xl mb-4">☕</span><p class="font-bold uppercase text-[10px]">Keranjang Kosong</p></div>`;
                totalElement.innerText = 'Rp 0';
                return;
            }

            container.innerHTML = cart.map((item, index) => `
                <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 text-gray-800">
                            <h4 class="font-black text-sm uppercase">${item.name}</h4>
                            <p class="text-[11px] text-orange-500 font-bold mt-1">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="removeFromCart(${index})" class="text-red-400 hover:text-red-600 font-bold text-xl">×</button>
                    </div>
                    <input type="text" placeholder="Catatan..." value="${item.note || ''}"
                        onchange="updateNote(${index}, this.value)"
                        class="w-full text-[10px] bg-orange-50 border-none rounded-lg focus:ring-1 focus:ring-orange-200 font-bold py-2 px-3">
                </div>
            `).join('');

            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            totalElement.innerText = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function updateNote(index, value) { cart[index].note = value; }
        function removeFromCart(index) { cart.splice(index, 1); renderCart(); }
        function clearCart() { cart = []; renderCart(); }

        function printReceipt(transactionId, items, totalPrice, cash, change) {
            let receiptContent = `
                <div style="font-family: 'Courier New', Courier, monospace; width: 280px; padding: 20px; color: #000;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">PREMIUM CAFE</h2>
                        <p style="font-size: 11px;">Jl. Raya Pendidikan No. 10</p>
                    </div>
                    <div style="font-size: 11px; border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 10px;">
                        ID: #${transactionId}<br>Tgl: ${new Date().toLocaleString('id-ID')}
                    </div>
                    <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                        ${items.map(item => `
                            <tr>
                                <td style="padding: 4px 0;">${item.name}${item.note ? '<br><small>*'+item.note+'</small>' : ''}</td>
                                <td style="text-align: right; vertical-align: top;">${item.qty}x</td>
                                <td style="text-align: right; vertical-align: top;">${(item.price * item.qty).toLocaleString('id-ID')}</td>
                            </tr>
                        `).join('')}
                    </table>
                    <div style="border-top: 1px dashed #000; margin-top: 10px; padding-top: 5px; font-size: 11px;">
                        <div style="display: flex; justify-content: space-between;"><b>TOTAL:</b> <b>Rp ${totalPrice.toLocaleString('id-ID')}</b></div>
                        <div style="display: flex; justify-content: space-between;">TUNAI: <span>Rp ${parseInt(cash).toLocaleString('id-ID')}</span></div>
                        <div style="display: flex; justify-content: space-between;">KEMBALI: <span>Rp ${change.toLocaleString('id-ID')}</span></div>
                    </div>
                    <p style="text-align: center; font-size: 10px; margin-top: 20px;">--- TERIMA KASIH ---</p>
                </div>
            `;
            let pWindow = window.open('', '', 'height=600,width=400');
            pWindow.document.write('<html><head><title>Struk</title></head><body>' + receiptContent + '</body></html>');
            pWindow.document.close();
            setTimeout(() => { pWindow.print(); pWindow.close(); }, 500);
        }

        function checkout() {
            if (cart.length === 0) return;
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            Swal.fire({
                title: 'Konfirmasi Bayar',
                html: `<p class="text-4xl font-black text-orange-600">Rp ${total.toLocaleString('id-ID')}</p><input type="number" id="cash-amount" class="swal2-input" placeholder="Uang Tunai">`,
                confirmButtonText: 'Bayar & Cetak',
                confirmButtonColor: '#f97316',
                showCancelButton: true,
                preConfirm: () => {
                    const cash = document.getElementById('cash-amount').value;
                    if (!cash || parseInt(cash) < total) { Swal.showValidationMessage(`Uang Kurang!`); }
                    return { cash: cash };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const cash = result.value.cash;
                    fetch('/checkout', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ total_price: total, items: cart })
                    }).then(res => res.json()).then(data => {
                        if (data.status === 'success') {
                            printReceipt(data.transaction_id, cart, total, cash, cash - total);
                            Swal.fire({ icon: 'success', title: 'Berhasil!', showConfirmButton: false, timer: 1500 })
                            .then(() => window.location.reload());
                        }
                    });
                }
            });
        }

        function filterCategory(category, btn) {
            currentCategory = category;
            document.querySelectorAll('.cat-btn').forEach(b => {
                b.className = "cat-btn bg-white text-gray-500 border border-orange-100 px-6 py-3 rounded-2xl text-xs font-black transition-all hover:bg-orange-50 uppercase tracking-wider";
            });
            btn.className = "cat-btn bg-orange-600 text-white px-8 py-3 rounded-2xl text-xs font-black transition-all shadow-lg uppercase tracking-wider";
            runFilter();
        }

        function filterMenu() { runFilter(); }
        function runFilter() {
            let searchInput = document.getElementById('searchMenu').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                let name = card.querySelector('.product-name').innerText.toLowerCase();
                let category = card.getAttribute('data-category');
                card.style.display = (name.includes(searchInput) && (currentCategory === 'all' || category === currentCategory)) ? "" : "none";
            });
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ffedd5; border-radius: 10px; }
        .product-card { transition: all 0.3s ease; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</x-app-layout>
