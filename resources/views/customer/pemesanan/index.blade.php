<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Pemesanan - Kemuning Catering</title>
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
            --text-light: #666; /* Lighter text for descriptions */
            --bg-light: #f8f9fa; /* Light background for the page */
            --bg-white: #ffffff; /* White background for cards */
            --shadow-light: rgba(0, 0, 0, 0.06); /* Softer shadow for elegance */
            --border-light: rgba(0, 0, 0, 0.06); /* Light border */
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
            padding-top: 80px; /* Space for fixed navbar */
        }

        /* ==========================================================================
           MAIN CONTENT STYLES
           ========================================================================== */
        .main-content-container {
            max-width: 1100px;
            margin: 4rem auto;
            padding: 0 1.5rem;
        }

        h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3.5rem;
            text-align: center;
            letter-spacing: 0.5px;
            position: relative;
        }

        h2::after {
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

        .card-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            justify-content: center;
        }

        .card {
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--shadow-light);
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: 1px solid var(--border-light);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            letter-spacing: 0.3px;
        }

        .card-text {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            flex-grow: 1;
            line-height: 1.8;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease-in-out;
            border: none;
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 165, 116, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #a78b5a 0%, #8B4513 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #8B4513 0%, #6b3a0f 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-accent {
            background: linear-gradient(135deg, #e6b98a 0%, #d4a574 100%);
            color: var(--text-dark);
        }

        .btn-accent:hover {
            background: linear-gradient(135deg, #d4a574 0%, #b88b4a 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 165, 116, 0.3);
        }

        /* ==========================================================================
           FOOTER STYLES
           ========================================================================== */
        .footer {
            text-align: center;
            padding: 3rem 1.5rem;
            margin-top: 6rem;
            color: var(--text-light);
            font-size: 0.95rem;
            background: var(--bg-white);
            border-top: 1px solid var(--border-light);
            letter-spacing: 0.3px;
        }

        .footer span {
            color: var(--primary-color);
        }

        /* ==========================================================================
           RESPONSIVE DESIGN
           ========================================================================== */
        @media (max-width: 768px) {
            .main-content-container {
                margin: 3rem auto;
                padding: 0 1rem;
            }

            h2 {
                font-size: 2.2rem;
                margin-bottom: 2.5rem;
            }

            .card-row {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .card {
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.8rem;
            }

            .card-body {
                padding: 2rem 1rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .card-text {
                font-size: 0.95rem;
            }

            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }

            .footer {
                padding: 2rem 1rem;
                font-size: 0.85rem;
            }
        }

        /* ==========================================================================
           ACCESSIBILITY
           ========================================================================== */
        .btn:focus,
        .card:hover {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        @media (prefers-contrast: high) {
            .btn-primary,
            .btn-secondary,
            .btn-accent {
                border: 2px solid var(--text-dark);
            }

            .card {
                border: 2px solid var(--text-dark);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .card,
            .btn,
            .nav-link,
            .nav-menu {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="main-content-container">
        <h2>Pilih Jenis Pemesanan</h2>

        <div class="card-row">
            <!-- Paket Box -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paket Box</h5>
                    <p class="card-text">Pesan paket box praktis dan lezat untuk berbagai acara, rapat, atau bekal.</p>
                    <a href="{{ route('pemesanan.paket-box') }}" class="btn btn-primary" aria-label="Pilih Paket Box">Pilih Paket</a>
                </div>
            </div>

            <!-- Prasmanan -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Prasmanan</h5>
                    <p class="card-text">Pilihan ideal untuk acara besar dengan hidangan lengkap dan penataan menarik.</p>
                    <a href="{{ route('pemesanan.prasmanan') }}" class="btn btn-secondary" aria-label="Pilih Prasmanan">Pilih Prasmanan</a>
                </div>
            </div>

            <!-- Custom Menu -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pondokan</h5>
                    <p class="card-text">Rancang menu impian Anda sendiri, sesuaikan dengan selera dan kebutuhan spesifik.</p>
                    <a href="{{ route('pemesanan.pondokan') }}" class="btn btn-accent" aria-label="Pilih Custom Menu">Pilih Pondokan</a>
                </div>
            </div>
            <!----Tumpeng Menu---->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tumpeng</h5>
                    <p class="card-text">Rancang menu impian Anda sendiri, sesuaikan dengan selera dan kebutuhan spesifik.</p>
                    <a href="{{ route('pemesanan.tumpeng') }}" class="btn btn-accent" aria-label="Pilih Tumpeng">Pilih Tumpeng</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2025 Kemuning Catering. All rights reserved. Dibuat dengan <span>❤</span>.
    </footer>
</body>
</html>