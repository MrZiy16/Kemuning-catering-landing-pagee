<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Kemuning Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ==========================================================================
           ROOT VARIABLES (Aligned with Navbar Colors)
           ========================================================================== */
        :root {
            --primary-color: #d4a574; /* Gold from navbar */
            --secondary-color: #c49660; /* Darker gold from navbar gradient */
            --accent-color: #8B4513; /* Darker brown for contrast */
            --text-dark: #333; /* Dark text */
            --text-light: #666; /* Lighter text */
            --bg-light: #f8f9fa; /* Light background */
            --bg-white: #ffffff; /* White background */
            --shadow-light: rgba(0, 0, 0, 0.06); /* Softer shadow */
            --border-light: rgba(0, 0, 0, 0.06); /* Light border */
            --navbar-height: 72px; /* Consistent with navbar height */
        }

        /* ==========================================================================
           GENERAL STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, var(--bg-light) 0%, #fff 100%);
            color: var(--text-dark);
            line-height: 1.7;
            overflow-x: hidden;
            padding-top: calc(var(--navbar-height) + 1rem); /* Prevent content overlap with fixed navbar */
        }

        /* ==========================================================================
           NAVBAR STYLES
           ========================================================================== */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            z-index: 9999;
            transition: all 0.3s ease-in-out;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px var(--shadow-light);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--navbar-height);
        }

        .nav-logo h2 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .nav-logo a {
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2.5rem;
            margin: 0;
            padding: 0;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s ease-in-out;
            position: relative;
            padding: 10px 0;
            display: block;
            letter-spacing: 0.3px;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        .auth-section {
            display: flex;
            align-items: center;
            margin-left: 2.5rem;
        }

        .login-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 8px 24px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 3px 12px rgba(212, 165, 116, 0.2);
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(212, 165, 116, 0.3);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            background: none;
            border: none;
            padding: 5px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: var(--text-dark);
            margin: 3px 0;
            transition: all 0.3s ease-in-out;
            display: block;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                left: -100%;
                top: var(--navbar-height);
                flex-direction: column;
                background: rgba(255, 255, 255, 0.98);
                width: 100%;
                text-align: center;
                transition: left 0.3s ease-in-out;
                padding: 2rem 0;
                backdrop-filter: blur(10px);
                box-shadow: 0 5px 20px var(--shadow-light);
                gap: 0;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-link {
                font-size: 1.1rem;
                padding: 1rem 0;
            }

            .hamburger {
                display: flex;
            }

            .auth-section {
                margin-left: 1rem;
            }

            .login-btn {
                padding: 6px 16px;
            }
        }

        /* ==========================================================================
           DETAIL PAGE STYLES
           ========================================================================== */
        .detail-container {
            max-width: 1100px;
            margin: 4rem auto;
            padding: 0 1.5rem;
        }

        .detail-container h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3rem;
            text-align: center;
            letter-spacing: 0.5px;
            position: relative;
        }

        .detail-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .detail-container h4 {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            letter-spacing: 0.3px;
        }

        .card {
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--shadow-light);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-bottom: 2px solid var(--border-light);
        }

        .card-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: 0.3px;
        }

        .card-text {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.8;
        }

        .price {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        ul {
            padding-left: 1.5rem;
            margin-bottom: 2rem;
        }

        li {
            font-size: 1rem;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            position: relative;
            padding-left: 1.5rem;
        }

        li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-size: 1.5rem;
            line-height: 1.2;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: 0.2px;
        }

        input,
        textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-dark);
            background: var(--bg-white);
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        input:focus,
        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(212, 165, 116, 0.2);
            outline: none;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            transition: all 0.3s ease-in-out;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 165, 116, 0.3);
        }

        /* ==========================================================================
           RESPONSIVE DESIGN
           ========================================================================== */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 15px;
                height: 60px;
            }

            .nav-logo h2 {
                font-size: 1.3rem;
            }

            .nav-menu {
                top: 60px;
            }

            .nav-link {
                font-size: 1rem;
            }

            .auth-section {
                margin-left: 1rem;
            }

            .login-btn {
                padding: 6px 16px;
                font-size: 0.9rem;
            }

            .detail-container {
                margin: 3rem auto;
                padding: 0 1rem;
            }

            .detail-container h2 {
                font-size: 2.2rem;
                margin-bottom: 2.5rem;
            }

            .detail-container h4 {
                font-size: 1.4rem;
            }

            .card {
                margin-bottom: 1.5rem;
            }

            .card-img-top {
                max-height: 300px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.6rem;
            }

            .card-text,
            .price,
            li {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: calc(60px + 0.5rem); /* Adjusted for mobile navbar height */
            }

            .nav-container {
                padding: 0 10px;
            }

            .nav-logo h2 {
                font-size: 1.2rem;
            }

            .detail-container h2 {
                font-size: 1.8rem;
            }

            .detail-container h4 {
                font-size: 1.2rem;
            }

            .card-img-top {
                max-height: 250px;
            }

            .card-body {
                padding: 1rem;
            }

            .card-title {
                font-size: 1.4rem;
            }

            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        /* ==========================================================================
           ACCESSIBILITY
           ========================================================================== */
        .btn:focus,
        input:focus,
        textarea:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        @media (prefers-contrast: high) {
            .card,
            input,
            textarea {
                border: 2px solid var(--text-dark);
            }

            .btn {
                border: 2px solid var(--text-dark);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .card,
            .btn,
            input,
            textarea,
            .nav-link,
            .hamburger span {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="#"><h2>Kemuning Catering</h2></a>
            </div>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#bestseller" class="nav-link">Best Seller</a></li>
                <li><a href="#testimonial" class="nav-link">Testimonial</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <div class="auth-section">
                <div class="login-btn-container">
                    <a href="#" class="login-btn">
                        <span class="login-text">Login</span>
                    </a>
                </div>
            </div>
            <button class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Detail Content -->
    <div class="detail-container">
        <h2>{{ $produk->nama_produk }}</h2>
        
        <div class="card">
            @if($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" 
                     class="card-img-top" 
                     alt="{{ $produk->nama_produk }}">
            @else
                <img src="https://via.placeholder.com/800x400/d4a574/ffffff?text=Tidak+Ada+Gambar" 
                     class="card-img-top" 
                     alt="{{ $produk->nama_produk }}">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $produk->nama_produk }}</h5>
                <p class="card-text">{{ $produk->deskripsi }}</p>
                <p class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <h4>Isi Paket:</h4>
        <ul>
            @forelse($produk->menu_items as $menu)
                <li>{{ $menu->nama_menu }} ({{ $menu->pivot->qty }}) - {{ $menu->deskripsi }}</li>
            @empty
                <li>Tidak ada menu dalam paket ini.</li>
            @endforelse
        </ul>

        <h4>Pesan Paket Ini:</h4>
        <form action="{{ route('pemesanan.paket-box.checkout', $produk->slug) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="quantity">Jumlah Paket</label>
                <input type="number" name="quantity" id="quantity" min="1" required>
            </div>
            <div class="form-group">
                <label for="tanggal_acara">Tanggal Acara</label>
                <input type="date" name="tanggal_acara" id="tanggal_acara" required>
            </div>
            <button type="submit" class="btn">Lanjut ke Pengiriman</button>
        </form>
    </div>

    <!-- Navbar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('nav-menu');
            const body = document.body;

            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
                body.classList.toggle('menu-open');
            });

            // Close mobile menu when clicking a nav link
            navMenu.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        hamburger.classList.remove('active');
                        navMenu.classList.remove('active');
                        body.classList.remove('menu-open');
                    }
                });
            });
        });
    </script>
</body>
</html>