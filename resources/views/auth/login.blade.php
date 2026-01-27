<x-guest-layout>
    <style>
        /* Menghilangkan background bawaan Laravel sepenuhnya */
        body, .min-h-screen {
            background: #F4F7FE !important; /* Warna abu muda kebiruan yang bersih */
            margin: 0;
            padding: 0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 1050px;
            display: flex;
            min-height: 620px;
        }

        /* Sisi Kiri: Form */
        .form-section {
            flex: 1;
            padding: 60px 80px; /* Spasi diperlebar agar tidak numpuk */
            display: flex;
            flex-direction: column;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px; /* Jarak logo ke judul diperlebar */
        }

        .brand-logo span {
            color: #FF5C00;
            font-size: 24px;
            font-weight: 800;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1B2559;
        }

        .subtitle {
            font-size: 14px;
            color: #A3AED0;
            line-height: 1.6;
            margin-bottom: 40px; /* Jarak deskripsi ke input diperlebar */
        }

        /* Styling Input agar lebih tinggi & lega */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1B2559;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            border: 1px solid #E0E5F2;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #FF5C00;
            box-shadow: 0 0 0 4px rgba(255, 92, 0, 0.05);
        }

        .btn-submit {
            background: #FF5C00;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #E55200;
            transform: translateY(-2px);
        }

        /* Sisi Kanan: Gambar */
        .image-section {
            flex: 1.1;
            background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1374&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        @media (max-width: 992px) {
            .image-section { display: none; }
            .form-section { padding: 50px 40px; }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="form-section">
                <div class="brand-logo">
                    <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" width="32" alt="">
                    <span>Cafe Premium</span>
                </div>

                <h2>Login form</h2>
                <p class="subtitle">
                    Selamat datang kembali! Silakan masuk ke akun Cafe Pro Anda untuk mulai mengelola pesanan.
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Username / Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter username/email" required autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #A3AED0; cursor: pointer;">
                            <input type="checkbox" name="remember" style="accent-color: #FF5C00;"> Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #FF5C00; font-size: 13px; font-weight: 700; text-decoration: none;">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">
                        Running order
                    </button>

                    <div style="text-align: center; margin-top: 30px; font-size: 14px; color: #A3AED0;">
                        Belum punya akun? <a href="{{ route('register') }}" style="color: #FF5C00; font-weight: 700; text-decoration: none;">Daftar Shift</a>
                    </div>

                    <div style="margin-top: auto; padding-top: 40px; text-align: center; font-size: 11px; color: #CBD5E0; letter-spacing: 1px;">
                        END USER AGREEMENT
                    </div>
                </form>
            </div>

            <div class="image-section"></div>
        </div>
    </div>
</x-guest-layout>
