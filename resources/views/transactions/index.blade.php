<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir - POS System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Pilih Menu</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($menus as $menu)
                        <div class="border p-3 rounded shadow-sm hover:bg-blue-50 cursor-pointer transition group"
                             onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                            <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-32 object-cover rounded mb-2 border group-hover:border-blue-300">
                            <p class="font-bold text-gray-800">{{ $menu->name }}</p>
                            <p class="text-green-600 font-semibold text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow h-fit sticky top-6">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Pesanan</h3>
                    <div id="cart-list" class="space-y-3 mb-4 min-h-[150px] max-h-[300px] overflow-y-auto">
                        <p class="text-gray-400 text-center italic">Belum ada pesanan</p>
                    </div>

                    <hr class="mb-4">

                    <div class="space-y-4 mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 tracking-wider">Tipe Pesanan</label>
                            <select id="order_type" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="Dine In">🏠 Dine In</option>
                                <option value="Take Away">🥡 Take Away</option>
                                <option value="Drive Thru">🚗 Drive Thru</option>
                                <option value="GoFood">🛵 GoFood</option>
                                <option value="ShopeeFood">🧡 ShopeeFood</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 tracking-wider">Metode Bayar</label>
                            <select id="payment_method" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="Tunai">💵 Tunai (Cash)</option>
                                <option value="QRIS">📱 QRIS / E-Wallet</option>
                                <option value="Transfer">🏦 Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between font-bold text-xl mb-6 px-2">
                        <span>Total:</span>
                        <span id="total-display" class="text-blue-600">Rp 0</span>
                    </div>

                    <button type="button" onclick="checkout()" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition transform active:scale-95">
                        PROSES BAYAR
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let total = 0;

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.menu_id === id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ menu_id: id, name: name, price: price, quantity: 1 });
            }
            updateCart();
        }

        function updateCart() {
            const list = document.getElementById('cart-list');
            const totalDisplay = document.getElementById('total-display');
            list.innerHTML = '';
            total = 0;

            if (cart.length === 0) {
                list.innerHTML = '<p class="text-gray-400 text-center italic">Belum ada pesanan</p>';
            } else {
                cart.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    list.innerHTML += `
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100 shadow-sm">
                            <div>
                                <p class="font-bold text-sm text-gray-800">${item.name}</p>
                                <p class="text-xs text-gray-500">${item.quantity}x @ Rp ${item.price.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-blue-700">Rp ${subtotal.toLocaleString('id-ID')}</p>
                                <button type="button" onclick="removeFromCart(${index})" class="text-red-500 text-[10px] uppercase font-bold tracking-wider hover:underline">Hapus</button>
                            </div>
                        </div>
                    `;
                });
            }
            totalDisplay.innerText = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function checkout() {
            if (cart.length === 0) return alert('Pilih menu dulu, Bos!');

            const orderType = document.getElementById('order_type').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Logika sederhana: Kalau QRIS pilih, kasih info ke kasir
            if(paymentMethod === 'QRIS') {
                alert("Silahkan arahkan pelanggan scan QRIS di meja kasir.");
            }

            fetch("{{ route('transaksi.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    total_price: total,
                    items: cart,
                    order_type: orderType,
                    payment_method: paymentMethod
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Gagal simpan transaksi');
                return data;
            })
            .then(data => {
                alert("MANTAP! Transaksi " + orderType + " Berhasil.");
                const printUrl = `/transaksi/${data.transaction_id}/print`;
                window.open(printUrl, '_blank', 'width=400,height=600');
                location.reload();
            })
            .catch(error => {
                alert("Waduh! Masalahnya: " + error.message);
            });
        }
    </script>
</x-app-layout>
