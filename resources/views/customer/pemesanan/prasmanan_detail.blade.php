<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Kemuning Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 1100px;
            margin: 4rem auto;
            padding: 0 1.5rem;
        }
        h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: #333;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card img {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
        }
        .card-body {
            padding: 2rem;
        }
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .card-text {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1.5rem;
        }
        .price {
            font-size: 1.4rem;
            font-weight: 600;
            color: #d4a574;
            margin-bottom: 1.5rem;
        }
        ul {
            padding-left: 1.5rem;
            margin-bottom: 2rem;
        }
        li {
            margin-bottom: 0.75rem;
            color: #444;
        }
        form {
            margin-top: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
        }
        input {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        input:focus {
            border-color: #d4a574;
            outline: none;
            box-shadow: 0 0 5px rgba(212,165,116,0.3);
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #d4a574, #c49660);
            color: #fff;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(135deg, #c49660, #8B4513);
            box-shadow: 0 5px 15px rgba(212,165,116,0.3);
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>

    <div class="container">
        <h2>{{ $produk->nama_produk }}</h2>

        <div class="card">
            @if($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
            @else
                <img src="https://via.placeholder.com/1000x400/d4a574/ffffff?text=Tidak+Ada+Gambar" alt="{{ $produk->nama_produk }}">
            @endif

            <div class="card-body">
                <h3 class="card-title">{{ $produk->nama_produk }}</h3>
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
        <form action="{{ route('pemesanan.prasmanan.checkout', $produk->slug) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="quantity">Jumlah Orang (Minimal 100)</label>
                <input type="number" name="quantity" id="quantity" min="100" value="100" required>
            </div>
            <div class="form-group">
                <label for="tanggal_acara">Tanggal Acara</label>
                <input type="date" name="tanggal_acara" id="tanggal_acara" required>
            </div>
            <button type="submit" class="btn">Lanjut ke Pengiriman</button>
        </form>
    </div>
</body>
</html>
