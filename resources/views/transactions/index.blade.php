<x-app-layout>
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
                    <div class="d-flex justify-content-between mb-3 px-2">
                        <h4 class="fw-bold m-0">Menu Kafe</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-3">Tersedia: {{ $menus->count() }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($menus as $menu)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm item-card"
                                 onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})"
                                 style="border-radius: 15px; overflow: hidden; cursor: pointer;">
                                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://via.placeholder.com/300' }}"
                                     style="height: 150px; object-fit: cover; width: 100%;">
                                <div class="card-body p-3 text-center">
                                    <h6 class="fw-bold mb-1">{{ $menu->name }}</h6>
                                    <p class="fw-bold mb-0" style="color:#ea580c">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                    <small class="text-muted">Stok: {{ $menu->stock }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 20px; top: 40px;">
                    <h5 class="fw-bold mb-4">Pesanan Baru</h5>
                    <div id="cart-list" style="min-height: 150px; max-height: 250px; overflow-y: auto;">
                        <p class="text-center text-muted py-5">Keranjang kosong</p>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="mb-2">
                            <label class="small fw-bold text-muted">Metode Pembayaran</label>
                            <select id="payment_method" class="form-select rounded-pill">
                                <option value="Midtrans">QRIS (Otomatis)</option>
                                <option value="Tunai">Tunai (Cash)</option>
                            </select>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="small fw-bold text-muted">Nomor Meja</label>
                                <input type="text" id="table_number" class="form-control rounded-pill" placeholder="01">
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold text-muted">Tipe</label>
                                <select id="order_type" class="form-select rounded-pill">
                                    <option value="Dine In">Makan di Sini</option>
                                    <option value="Take Away">Bungkus</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted">Catatan Pesanan</label>
                            <textarea id="note" class="form-control border-0 bg-light mt-1" rows="2" placeholder="Contoh: Es sedikit..." style="border-radius: 12px; resize: none;"></textarea>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <h4 class="fw-bold">Total</h4>
                            <h4 class="fw-bold text-orange" style="color:#ea580c">Rp <span id="total-display">0</span></h4>
                        </div>

                        <button onclick="checkout()" id="btn-bayar" class="btn btn-dark w-100 py-3 rounded-pill fw-bold shadow">
                            BAYAR SEKARANG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price) {
            let item = cart.find(i => i.id === id);
            if (item) { item.quantity += 1; } else { cart.push({ id, name, price, quantity: 1 }); }
            renderCart();
        }

        function renderCart() {
            const list = document.getElementById('cart-list');
            const totalDisplay = document.getElementById('total-display');
            let total = 0;
            if (cart.length === 0) {
                list.innerHTML = '<p class="text-center text-muted py-5">Keranjang kosong</p>';
                totalDisplay.innerText = "0";
                return;
            }
            list.innerHTML = '';
            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                list.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded-3">
                    <div class="text-truncate" style="max-width: 140px;">
                        <b>${item.name}</b><br>
                        <small>${item.quantity}x Rp ${item.price.toLocaleString()}</small>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button onclick="updateQty(${index}, -1)" class="btn btn-sm btn-light border">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQty(${index}, 1)" class="btn btn-sm btn-light border">+</button>
                    </div>
                </div>`;
            });
            totalDisplay.innerText = total.toLocaleString('id-ID');
        }

        function updateQty(index, delta) {
            cart[index].quantity += delta;
            if (cart[index].quantity <= 0) cart.splice(index, 1);
            renderCart();
        }

        async function checkout() {
            if (cart.length === 0) return alert('Keranjang masih kosong!');
            const method = document.getElementById('payment_method').value;
            const btn = document.getElementById('btn-bayar');

            btn.disabled = true;
            btn.innerText = 'Memproses...';

            const payload = {
                total_price: cart.reduce((acc, i) => acc + (i.price * i.quantity), 0),
                items: cart,
                order_type: document.getElementById('order_type').value,
                table_number: document.getElementById('table_number').value,
                payment_method: method,
                note: document.getElementById('note').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("transaksi.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();

                if (response.ok) {
                    if (method === 'Midtrans') {
                        // Munculkan Popup Midtrans Snap
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.open(`/transaksi/${data.transaction_id}/print`, '_blank');
                                location.reload();
                            },
                            onPending: function(result) { alert('Menunggu pembayaran...'); location.reload(); },
                            onError: function(result) { alert('Pembayaran Gagal!'); btn.disabled = false; }
                        });
                    } else {
                        // Langsung print jika Tunai
                        window.open(`/transaksi/${data.transaction_id}/print`, '_blank');
                        location.reload();
                    }
                } else {
                    alert('Gagal: ' + data.message);
                    btn.disabled = false;
                }
            } catch (error) {
                alert('Sistem Error!');
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>
