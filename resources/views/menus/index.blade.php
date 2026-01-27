<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Kelola Menu Cafe</h2>
                <p class="text-muted">Daftar semua makanan, minuman, dan snack</p>
            </div>
            <a href="{{ route('menus.create') }}" class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm btn-hover-zoom" style="background-color: #ea580c;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Menu Baru
            </a>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-8">
                <form action="{{ route('menus.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 shadow-none py-2"
                               placeholder="Cari nama menu..." value="{{ request('search') }}">
                        <button class="btn btn-dark px-4" type="submit">Cari</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{ route('menus.index') }}" method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="category" class="form-select shadow-sm rounded-pill py-2 border-0" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <option value="Makanan" {{ request('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="Minuman" {{ request('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Snack" {{ request('category') == 'Snack' ? 'selected' : '' }}>Snack</option>
                    </select>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="table-responsive p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="border-0 px-4">Foto</th>
                            <th class="border-0">Nama Menu</th>
                            <th class="border-0">Kategori</th>
                            <th class="border-0 text-end px-4">Harga</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                        <tr>
                            <td class="px-4">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="rounded shadow-sm object-fit-cover" width="50" height="50">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><div class="fw-bold">{{ $menu->name }}</div></td>
                            <td><span class="badge bg-light text-dark border">{{ $menu->category }}</span></td>
                            <td class="text-end px-4 fw-bold text-orange" style="color: #ea580c;">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Pindahkan menu ke arsip database?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-egg-fried fs-1 d-block mb-2"></i>
                                Menu tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .btn-hover-zoom { transition: 0.3s; }
        .btn-hover-zoom:hover { transform: scale(1.05); }
        .table-hover tbody tr:hover { background-color: #fffaf8; }
    </style>
</x-app-layout>
