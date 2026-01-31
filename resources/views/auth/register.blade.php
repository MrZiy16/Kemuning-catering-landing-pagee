<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f8fffe;
    }

    nav {
      background: #fff;
      padding: 16px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .logo {
      font-size: 24px;
      font-weight: 700;
      color: #c8a882;
      text-decoration: none;
    }

    .login-btn {
      background: #c8a882;
      color: #fff;
      padding: 10px 24px;
      border: none;
      border-radius: 25px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }

    .login-btn:hover {
      background: #b39470;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(200, 168, 130, 0.3);
    }

    .login-btn svg {
      width: 16px;
      height: 16px;
    }

    .main-content {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: calc(100vh - 70px);
      padding: 20px;
    }

    .card {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(46, 204, 113, 0.1);
      width: 100%;
      max-width: 450px;
      border: 1px solid #e8f5e8;
    }

    .card h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #2d5a3d;
      font-size: 24px;
      font-weight: 600;
    }

    label {
      font-weight: 500;
      color: #2d5a3d;
      font-size: 14px;
      display: block;
      margin-bottom: 8px;
    }

    input, textarea {
      width: 100%;
      padding: 12px 16px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 2px solid #e8f5e8;
      font-size: 14px;
      box-sizing: border-box;
      transition: all 0.3s ease;
      background: #fdfffe;
    }

    input:focus, textarea:focus {
      border-color: #52c788;
      outline: none;
      box-shadow: 0 0 0 3px rgba(82, 199, 136, 0.1);
      background: #fff;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    .btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #52c788, #2ecc71);
      border: none;
      color: white;
      font-size: 16px;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(46, 204, 113, 0.3);
    }

    .btn:hover {
      background: linear-gradient(135deg, #47b77a, #27ae60);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(46, 204, 113, 0.4);
    }

    .btn:active {
      transform: translateY(0);
    }

    .footer-link {
      text-align: center;
      margin-top: 25px;
      font-size: 14px;
      color: #6b7280;
    }

    .footer-link a {
      color: #52c788;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .footer-link a:hover {
      color: #2ecc71;
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      nav {
        padding: 16px 20px;
      }

      .logo {
        font-size: 20px;
      }

      .login-btn {
        padding: 8px 16px;
        font-size: 12px;
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 30px 25px;
      }
      
      .main-content {
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <nav>
    <a href="/" class="logo">Kemuning Catering</a>
   
  </nav>

  <div class="main-content">
    <div class="card">
      <h2>Daftar Akun</h2>
      <form action="{{ route('register') }}" method="POST">
        @csrf

        <!-- Nama -->
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>

        <!-- Email -->
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <!-- Nomor HP -->
        <label for="no_hp">Nomor HP</label>
        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">

        <!-- Alamat -->
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat">{{ old('alamat') }}</textarea>

        <!-- Password -->
        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" required>

        <!-- Konfirmasi Password -->
        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <!-- Tombol -->
        <button type="submit" class="btn">Daftar</button>
      </form>

      <div class="footer-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
      </div>
    </div>
  </div>
</body>
</html>