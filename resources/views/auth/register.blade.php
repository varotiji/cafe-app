<x-guest-layout>
    <style>
        body, .min-h-screen {
            background: #F4F7FE !important;
            margin: 0;
            padding: 0;
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-card {
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 1050px;
            display: flex;
            min-height: 650px;
        }

        .form-section {
            flex: 1;
            padding: 50px 80px;
            display: flex;
            flex-direction: column;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .brand-logo span {
            color: #FF5C00;
            font-size: 24px;
            font-weight: 800;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1B2559;
        }

        .subtitle {
            font-size: 14px;
            color: #A3AED0;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1B2559;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 20px;
            border-radius: 14px;
            border: 1px solid #E0E5F2;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #FF5C00;
            box-shadow: 0 0 0 4px rgba(255, 92, 0, 0.05);
            outline: none;
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
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #E55200;
            transform: translateY(-2px);
        }

        .image-section {
            flex: 1.1;
            background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=1374&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        @media (max-width: 992px) {
            .image-section { display: none; }
            .form-section { padding: 40px; }
        }
    </style>

    <div class="register-wrapper">
        <div class="register-card">
            <div class="form-section">
                <div class="brand-logo">
                    <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" width="32" alt="">
                    <span>Coca</span>
                </div>

                <h2>Daftar Kasir</h2>
                <p class="subtitle">Registrasi akun kasir berdasarkan shift untuk akses keamanan PIN.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama kasir" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username / Email</label>
                        <input type="email" name="email" class="form-control" placeholder="kasir@coca.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Shift Kerja</label>
                        <select name="shift" class="form-select" required>
                            <option value="PAGI">PAGI (06:00 - 15:00)</option>
                            <option value="SIANG">SIANG (15:00 - 19:00)</option>
                            <option value="MALAM">MALAM (19:00 - 05:00)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Buat PIN Akses (6 Digit)</label>
                                <input type="password" name="password" class="form-control" placeholder="Contoh: 123456" maxlength="6" pattern="\d*" inputmode="numeric" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Konfirmasi PIN</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi PIN" maxlength="6" pattern="\d*" inputmode="numeric" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Daftar Shift & PIN
                    </button>

                    <div style="text-align: center; margin-top: 25px; font-size: 14px; color: #A3AED0;">
                        Sudah punya akses? <a href="{{ route('login') }}" style="color: #FF5C00; font-weight: 700; text-decoration: none;">Masuk Kasir</a>
                    </div>
                </form>
            </div>
            <div class="image-section"></div>
        </div>
    </div>
</x-guest-layout>
