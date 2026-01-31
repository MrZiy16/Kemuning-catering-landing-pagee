<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Box Tersedia - Kemuning Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #d4a574;
            --secondary-color: #c49660;
            --accent-color: #8B4513;
            --text-dark: #333;
            --text-light: #666;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --shadow-light: rgba(0, 0, 0, 0.06);
            --border-light: rgba(0, 0, 0, 0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, var(--bg-light) 0%, #fff 100%);
            color: var(--text-dark);
            line-height: 1.7;
            overflow-x: hidden;
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
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

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            justify-content: center;
        }

        .card {
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--shadow-light);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: 1px solid var(--border-light);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 2px solid var(--border-light);
        }

        .card-body {
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            letter-spacing: 0.3px;
        }

        .card-text {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
            flex-grow: 1;
            line-height: 1.8;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
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
            margin-top: auto;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 165, 116, 0.3);
        }

        .no-products {
            text-align: center;
            font-size: 1.2rem;
            color: var(--text-light);
            padding: 2rem;
            width: 100%;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .row { grid-template-columns: 1fr; gap: 2rem; }
            .card-img-top { height: 200px; }
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="container">
        <h2>Nasi Tumpeng </h2>
        <div class="row">
            @forelse ($produk as $item)
                <div class="card">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->nama_produk }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->nama_produk }}</h5>
                        <p class="card-text">{{ $item->deskripsi }}</p>
                        <p class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('pemesanan.paket-box.detail', $item->slug) }}" class="btn">Lihat Detail</a>
                    </div>
                </div>
            @empty
                <p class="no-products">Belum ada nasi tumpeng yang tersedia.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
