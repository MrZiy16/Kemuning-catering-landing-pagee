
    <div class="profile-page">
        <div class="profile-card">
            <h2 class="title">Pengaturan Profil</h2>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('profile.update') }}" class="form">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $user->nama) }}"
                        placeholder="Masukkan nama lengkap"
                        required
                    />
                    @if ($errors->has('nama'))
                        <small class="error-text">{{ $errors->first('nama') }}</small>
                    @endif
                </div>

                
                <div class="form-group">
                    <label for="no_hp">Telepon</label>
                    <input
                        type="text"
                        id="no_hp"
                        name="no_hp"
                        value="{{ old('no_hp', $user->no_hp ?? '') }}"
                        placeholder="08xxxxxxxxxx"
                    />
                    @if ($errors->has('no_hp'))
                        <small class="error-text">{{ $errors->first('no_hp') }}</small>
                    @endif
                </div>

                <div class="divider"></div>

                <fieldset class="fieldset">
                    <legend>Ubah Kata Sandi (Opsional)</legend>

                    <div class="form-group">
                        <label for="kata_sandi">Kata Sandi Baru</label>
                        <input
                            type="password"
                            id="kata_sandi"
                            name="kata_sandi"
                            placeholder="Minimal 6 karakter"
                        />
                        @if ($errors->has('kata_sandi'))
                            <small class="error-text">{{ $errors->first('kata_sandi') }}</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="kata_sandi_confirmation">Konfirmasi Kata Sandi</label>
                        <input
                            type="password"
                            id="kata_sandi_confirmation"
                            name="kata_sandi_confirmation"
                            placeholder="Ulangi kata sandi baru"
                        />
                    </div>
                </fieldset>

                <div class="actions">
                    <button type="submit" class="btn primary">Simpan Perubahan</button>
                </div>
            </form>
          
            <div class="actions" style="margin-top: 10px;">
                <a href="{{ route('home') }}" class="btn" style="background: #ddd; color: #333;">Kembali</a>
            </div>
        </div>
    </div>

    <style>
        .profile-page {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            background: #f7f7f9;
        }

        .profile-card {
            width: 100%;
            max-width: 720px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 28px;
            border: 1px solid rgba(0,0,0,0.06);
        }

        .title {
            margin: 0 0 16px 0;
            font-size: 22px;
            color: #222;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert.success {
            background: #eaf7ec;
            color: #2b8a3e;
            border: 1px solid #cfead5;
        }
        .alert.error {
            background: #fff1f0;
            color: #c0392b;
            border: 1px solid #ffd4d1;
        }
        .alert ul {
            margin: 8px 0 0 18px;
        }

        .form {
            display: block;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
            background: #fff;
        }

        input:focus {
            border-color: #d4a574;
            box-shadow: 0 0 0 3px rgba(212,165,116,0.15);
        }

        .error-text {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #c0392b;
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }

        .fieldset {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 14px;
        }

        .fieldset legend {
            padding: 0 6px;
            font-size: 14px;
            color: #555;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn.primary {
            background: linear-gradient(135deg, #d4a574 0%, #c49660 100%);
            color: #fff;
            box-shadow: 0 3px 12px rgba(212,165,116,0.28);
        }
        .btn.primary:hover {
            filter: brightness(0.98);
            transform: translateY(-1px);
        }

        @media (max-width: 560px) {
            .profile-card {
                padding: 18px;
                border-radius: 12px;
            }
        }
    </style>

