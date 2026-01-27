<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cafe Pro Management</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7fe; overflow-x: hidden; }
        .wrapper { display: flex; align-items: stretch; width: 100%; }
        #sidebar {
            min-width: 260px; max-width: 260px;
            background: white; border-right: 1px solid #eef2f6;
            position: fixed; height: 100vh; z-index: 1000;
        }
        #content { width: 100%; margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: white; padding: 15px 30px; border-bottom: 1px solid #eef2f6;
            display: flex; justify-content: flex-end; align-items: center;
        }
        .nav-link-custom {
            color: #64748b; padding: 12px 20px; border-radius: 12px;
            margin: 5px 15px; display: flex; align-items: center; gap: 12px;
            text-decoration: none; font-weight: 600; transition: 0.2s;
        }
        .nav-link-custom:hover { background: #fef2f2; color: #ea580c; }
        .nav-link-custom.active { background: #ea580c; color: white !important; }

        @media print {
            #sidebar, .topbar, .btn, .no-print { display: none !important; }
            #content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="p-4 border-bottom text-center">
                <h4 class="fw-bold text-dark mb-0">☕ Cafe <span style="color: #ea580c;">Pro</span></h4>
            </div>

            <div class="nav flex-column mt-3">
                @if(Auth::user()->role == 'admin')
                    <div class="px-4 small fw-bold text-uppercase text-muted mt-2 mb-2" style="font-size: 10px;">Manajemen Admin</div>
                    <a href="{{ route('dashboard') }}" class="nav-link-custom {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('menus.index') }}" class="nav-link-custom {{ Request::is('menus*') ? 'active' : '' }}">
                        <i class="bi bi-egg-fried"></i> Kelola Menu
                    </a>
                    <hr class="mx-3 my-3">
                @endif

                <div class="px-4 small fw-bold text-uppercase text-muted mb-2" style="font-size: 10px;">Penjualan</div>
                <a href="{{ route('pos') }}" class="nav-link-custom {{ Request::is('pos*') ? 'active' : '' }}">
                    <i class="bi bi-calculator"></i> Kasir / POS
                </a>
                <a href="{{ route('history') }}" class="nav-link-custom {{ Request::is('history*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Penjualan
                </a>

                <div class="mt-auto pb-4">
                    <hr class="mx-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link-custom text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div id="content">
            <header class="topbar no-print">
                <div class="dropdown">
                    <button class="btn border-0 d-flex align-items-center bg-light rounded-3" type="button" data-bs-toggle="dropdown">
                        <div class="text-end me-2">
                            <small class="text-muted d-block fw-bold" style="font-size: 9px;">{{ strtoupper(Auth::user()->role) }}</small>
                            <span class="fw-bold" style="font-size: 13px;">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="bi bi-person-circle fs-4" style="color: #ea580c;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-bold">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="p-4">
                {{ $slot }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
