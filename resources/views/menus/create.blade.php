<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container py-5">
        <div class="card border-0 shadow-lg" style="border-radius: 20px;">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('menus.index') }}" class="btn btn-light rounded-circle me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Tambah Menu Baru</h2>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold">Nama Menu</label>
                        <input type="text" name="name" class="form-control border-2 shadow-none" placeholder="Contoh: Matcha Latte" value="{{ old('name') }}" required style="border-radius: 12px;">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Kategori</label>
                        <select name="category" class="form-select border-2 shadow-none" style="border-radius: 12px;">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control border-2 shadow-none" placeholder="15000" value="{{ old('price') }}" required style="border-radius: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Stok Awal</label>
                            <input type="number" name="stock" class="form-control border-2 shadow-none" placeholder="50" value="{{ old('stock') }}" required style="border-radius: 12px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold">Foto Menu</label>
                        <input type="file" name="image" class="form-control border-2 shadow-none" style="border-radius: 12px;">
                        <small class="text-muted">Maksimal ukuran file: 5MB</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('menus.index') }}" class="btn btn-light px-4 border rounded-pill">BATAL</a>
                        <button type="submit" class="btn text-white px-5 shadow-sm rounded-pill" style="background-color: #ea580c;">SIMPAN MENU</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
