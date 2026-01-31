<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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

    .container {
      max-width: 450px;
      width: 100%;
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(46, 204, 113, 0.1);
      border: 1px solid #e8f5e8;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #2d5a3d;
      font-size: 24px;
      font-weight: 600;
    }

    .alert {
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      font-weight: 500;
    }

    .alert-success {
      background: #e8f5e8;
      color: #2d5a3d;
      border: 1px solid #c3e6c3;
    }

    .alert-error {
      background: #fef2f2;
      color: #dc2626;
      border: 1px solid #fecaca;
    }

    .alert ul {
      margin: 0;
      padding-left: 18px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #2d5a3d;
      font-size: 14px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 16px;
      margin-bottom: 20px;
      border: 2px solid #e8f5e8;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
      transition: all 0.3s ease;
      background: #fdfffe;
    }

    input:focus {
      border-color: #52c788;
      outline: none;
      box-shadow: 0 0 0 3px rgba(82, 199, 136, 0.1);
      background: #fff;
    }

    .checkbox {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
      color: #2d5a3d;
    }

    .checkbox input {
      margin-right: 8px;
      width: 16px;
      height: 16px;
      accent-color: #52c788;
    }

    .checkbox label {
      margin-bottom: 0;
      font-weight: 400;
      cursor: pointer;
    }

    .btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #52c788, #2ecc71);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      font-weight: 600;
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

    .links {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
      color: #6b7280;
    }

    .links a {
      color: #52c788;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .links a:hover {
      color: #2ecc71;
      text-decoration: underline;
    }

    .form-group {
      margin-bottom: 0;
    }

    .forgot-password {
      margin-bottom: 10px;
    }

    .register-link {
      border-top: 1px solid #e8f5e8;
      padding-top: 20px;
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
      .container {
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
    <div class="container">
      <h2>Masuk</h2>

      <!-- Flash Message -->
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn">Masuk</button>

        <div class="links forgot-password">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
          @endif
        </div>
      </form>
      
      <div class="links register-link">
        <span>Belum punya akun? </span>
        <a href="{{ route('register') }}">Daftar Sekarang</a>
      </div>
    </div>
  </div>
</body>
</html>