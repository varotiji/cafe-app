<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container py-5">
        <div class="card border-0 shadow-lg" style="border-radius: 20px;">
            <div class="card-body p-5">
                <h2 class="fw-bold mb-4">Edit Menu: {{ $menu->name }}</h2>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="fw-bold">Nama Menu</label>
                        <input type="text" name="name" class="form-control border-2 shadow-none" value="{{ old('name', $menu->name) }}" required style="border-radius: 12px;">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-dark">Kategori</label>
                        <select name="category" class="form-select border-2 shadow-none" style="border-radius: 12px;">
                            <option value="Makanan" {{ $menu->category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ $menu->category == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Snack" {{ $menu->category == 'Snack' ? 'selected' : '' }}>Snack</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Harga</label>
                            <input type="number" name="price" class="form-control border-2 shadow-none" value="{{ old('price', $menu->price) }}" required style="border-radius: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Stok</label>
                            <input type="number" name="stock" class="form-control border-2 shadow-none" value="{{ old('stock', $menu->stock) }}" required style="border-radius: 12px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold d-block">Foto Menu</label>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-4 border-2 border-dashed">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}?v={{ time() }}" width="100" height="100" class="rounded shadow-sm border object-fit-cover">
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="image" class="form-control border-2 shadow-none" style="border-radius: 12px;">
                                <small class="text-muted">Biarkan kosong jika tidak ingin ganti foto</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('menus.index') }}" class="btn btn-light px-4 border rounded-pill">Batal</a>
                        <button type="submit" class="btn text-white px-5 shadow-sm rounded-pill" style="background-color: #ea580c;">Update Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .border-dashed { border-style: dashed !important; }
        .form-control:focus, .form-select:focus {
            border-color: #ea580c !important;
            box-shadow: 0 0 0 0.25rem rgba(234, 88, 12, 0.1) !important;
        }
    </style>
</x-app-layout>
