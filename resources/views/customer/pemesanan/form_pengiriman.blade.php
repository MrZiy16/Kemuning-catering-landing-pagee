<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengiriman Pesanan</title>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* 🎯 Background putih polos seperti permintaan */
        body {
            background: #ffffff; /* Putih polos */
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Biar tidak center vertikal, lebih natural di halaman */
            padding-top: 40px;
        }

        .container {
            max-width: 900px;
            width: 100%;
          margin-top: 3rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); /* Shadow tetap ada biar card 'mengambang' */
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #667eea, #764ba2);
            border-radius: 2px;
        }

        .order-summary {
            background: #f8f9fa;
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #27ae60;
        }

        .order-summary p {
            margin: 0.3rem 0;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        input, textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder, textarea::placeholder {
            color: #aaa;
        }

        .btn {
            display: block;
            width: 100%;
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }

        .btn:hover {
            background: linear-gradient(to right, #5a6fd8, #6a4291);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Responsif */
        @media (max-width: 768px) {
            .card {
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.75rem;
            }

            .btn {
                padding: 1rem;
                font-size: 1rem;
            }
        }

        /* Animasi muncul */
        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body>
    <x-navbar></x-navbar>

    <div class="container">
        <div class="card fade-in">
            <h2><i class="fas fa-truck me-2"></i> Form Pengiriman</h2>

            <!-- Ringkasan Pesanan -->
   <div class="order-summary">
    @if($checkout['tipe'] === 'custom')
        <p>
            <i class="fas fa-utensils"></i> 
            <strong>Pesanan Custom Menu:</strong>
        </p>
        @foreach($checkout['menus'] as $menu)
            <p style="margin-left: 20px;">
                • {{ $menu['nama_menu'] }} × {{ $menu['qty'] }}
                <span style="color: #27ae60; font-weight: 600;">
                    (Rp {{ number_format($menu['harga'] * $menu['qty'], 0, ',', '.') }})
                </span>
            </p>
        @endforeach
        <?php 
        $totalCustom = 0;
        foreach($checkout['menus'] as $menu) {
            $totalCustom += $menu['harga'] * $menu['qty'];
        }
        ?>
        <p style="border-top: 1px solid #ddd; padding-top: 8px; margin-top: 8px;">
            <i class="fas fa-calculator"></i>
            <strong>Total: Rp {{ number_format($totalCustom, 0, ',', '.') }}</strong>
        </p>
    @else
        <p>
            <i class="fas fa-box"></i> 
            <strong>Pesanan:</strong> {{ $checkout['produk_nama'] }}
            @if($checkout['tipe'] === 'paket_box')
                × {{ $checkout['quantity'] }}
            @elseif($checkout['tipe'] === 'prasmanan')
                <br>
                <small>
                    @foreach($checkout['menus'] as $menu)
                        • {{ $menu['nama_menu'] }} ({{ $menu['qty'] }})<br>
                    @endforeach
                </small>
            @endif
        </p>
    @endif
    
    <p>
        <i class="far fa-calendar-alt"></i> 
        <strong>Tanggal Acara:</strong> {{ $checkout['tanggal_acara'] }}
    </p>
</div>

            <!-- Form -->
            <form action="{{ route('pemesanan.konfirmasi') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</label>
                    <textarea 
                        name="alamat" 
                        id="alamat" 
                        rows="4" 
                        placeholder="Masukkan alamat lengkap Anda (termasuk RT/RW, kelurahan, kecamatan, kota, provinsi, kode pos)" 
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="waktu_acara"><i class="far fa-clock"></i> Waktu Acara</label>
                    <input 
                        type="time" 
                        name="waktu_acara" 
                        id="waktu_acara" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="catatan"><i class="fas fa-sticky-note"></i> Catatan Khusus</label>
                    <textarea 
                        name="catatan" 
                        id="catatan" 
                        rows="3" 
                        placeholder="Contoh: 'Letakkan di depan rumah', 'Hubungi via WA sebelum datang'"
                    ></textarea>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-check-circle"></i> Konfirmasi & Lanjutkan Pemesanan
                </button>
            </form>
        </div>
    </div>
</body>
</html>