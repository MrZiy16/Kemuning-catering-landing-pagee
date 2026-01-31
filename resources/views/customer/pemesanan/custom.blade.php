<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan Pondokan - Kemuning Catering</title>
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
            padding: 100px 20px 20px 20px;
            min-height: 100vh;
            position: relative;
        }

        /* Fix horizontal scroll issue */
        html {
            overflow-x: hidden;
            width: 100%;
        }

        * {
            box-sizing: border-box;
        }

        /* Fix navbar mobile menu overflow issue */
        @media screen and (max-width: 768px) {
            .nav-menu {
                position: fixed !important;
                left: -100% !important;
                right: auto !important;
                width: 100% !important;
                max-width: 100vw !important;
            }
            
            .nav-menu.active {
                left: 0 !important;
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3rem;
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

        .content-wrapper {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 2rem;
        }

        .card {
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--shadow-light);
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: var(--bg-white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
        }

        .menu-list {
            display: grid;
            gap: 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: var(--bg-white);
        }

        .menu-item:hover {
            border-color: var(--primary-color);
            background: rgba(212, 165, 116, 0.03);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--shadow-light);
        }

        .menu-item.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.08) 0%, rgba(196, 150, 96, 0.08) 100%);
        }

        .menu-checkbox {
            width: 22px;
            height: 22px;
            cursor: pointer;
            margin-right: 1rem;
            accent-color: var(--primary-color);
        }

        .menu-info {
            flex: 1;
        }

        .menu-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1.05rem;
            margin-bottom: 0.4rem;
        }

        .menu-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 0.4rem;
            font-weight: 500;
        }

        .btn {
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.3px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            width: 100%;
        }

        .btn-success:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .cart-sidebar {
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .cart-item {
            padding: 1.25rem;
            background: var(--bg-light);
            border-radius: 12px;
            margin-bottom: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-light);
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
            font-size: 0.95rem;
        }

        .cart-item-price {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .qty-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            color: white;
        }

        .qty-label {
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
            font-weight: 500;
            opacity: 0.95;
        }

        .qty-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .qty-input-main {
            flex: 1;
            padding: 0.9rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
        }

        .qty-input-main:focus {
            outline: none;
            border-color: white;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        .qty-info {
            font-size: 0.8rem;
            margin-top: 0.6rem;
            opacity: 0.9;
        }

        .cart-total {
            padding: 1.5rem 0;
            border-top: 2px solid var(--border-light);
            margin-top: 1.5rem;
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .total-price {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.6rem;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-light);
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            background: #e8f4f8;
            color: #0c5460;
            border: 1px solid #bee5eb;
            font-size: 0.9rem;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
            margin-bottom: 1.5rem;
        }

        .cart-count {
            background: white;
            color: var(--primary-color);
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-left: 0.5rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }

            .cart-sidebar {
                position: static;
                max-height: none;
            }

            h2 {
                font-size: 2rem;
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="container">
        <h2>🍱 Pemesanan Pondokan</h2>

        <div class="content-wrapper">
            <!-- Daftar Produk -->
            <div class="card">
                <div class="card-header">
                    Pilih Menu Pondokan
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="form-group">
                            <label class="form-label">📅 Tanggal Acara</label>
                            <input type="date" class="form-control" id="tanggal_acara" 
                                   min="{{ date('Y-m-d') }}" 
                                   value="{{ $cart['tanggal_acara'] ?? '' }}"
                                   required>
                        </div>

                        <div id="alertTanggal" class="alert-warning" style="display: none;">
                            ⚠️ Silakan pilih tanggal acara terlebih dahulu
                        </div>

                        <div class="menu-list">
                            @foreach($products as $product)
                            <div class="menu-item {{ isset($cart['items'][$product->id_produk]) ? 'selected' : '' }}" 
                                 data-id="{{ $product->id_produk }}">
                                <input type="checkbox" 
                                       class="menu-checkbox" 
                                       data-id="{{ $product->id_produk }}"
                                       data-nama="{{ $product->nama_produk }}"
                                       data-harga="{{ $product->harga }}"
                                       {{ isset($cart['items'][$product->id_produk]) ? 'checked' : '' }}>
                                <div class="menu-info">
                                    <div class="menu-name">{{ $product->nama_produk }}</div>
                                    <div class="menu-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                    @if($product->jumlah_orang)
                                    <span class="badge">{{ $product->jumlah_orang }} Porsi</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert">Tidak ada produk pondokan tersedia</div>
                    @endif
                </div>
            </div>

            <!-- Keranjang -->
            <div class="cart-sidebar">
                <div class="card">
                    <div class="card-header">
                        Keranjang <span class="cart-count" id="cartCount">{{ count($cart['items']) }}</span>
                    </div>
                    <div class="card-body" id="cartContainer">
                        @if(count($cart['items']) > 0)
                            <!-- Qty Total Section -->
                            <div class="qty-section">
                                <div class="qty-label">🍽️ Jumlah Total Porsi</div>
                                <div class="qty-input-wrapper">
                                    <input type="number" 
                                           class="qty-input-main" 
                                           id="qtyTotal"
                                           min="100" 
                                           value="{{ $cart['qty_total'] }}"
                                           placeholder="Minimal 100 porsi">
                                </div>
                                <div class="qty-info">
                                    ℹ️ Minimal pemesanan 100 porsi untuk {{ count($cart['items']) }} menu
                                </div>
                            </div>

                            <div id="cartItems">
                                @foreach($cart['items'] as $item)
                                    <div class="cart-item" data-id="{{ $item['id_produk'] }}">
                                        <div class="cart-item-info">
                                            <div class="cart-item-name">{{ $item['nama_produk'] }}</div>
                                            <div class="cart-item-price">
                                                Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-icon btn-remove" 
                                                data-id="{{ $item['id_produk'] }}"
                                                title="Hapus">
                                            🗑️
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="cart-total">
                                @php
                                    $totalHarga = 0;
                                    foreach($cart['items'] as $item) {
                                        $totalHarga += $item['harga'];
                                    }
                                    $grandTotal = $totalHarga * $cart['qty_total'];
                                @endphp
                                <div class="cart-total-row">
                                    <span style="color: var(--text-light); font-size: 0.9rem;">{{ count($cart['items']) }} menu × {{ $cart['qty_total'] }} porsi</span>
                                </div>
                                <div class="cart-total-row">
                                    <strong>Total:</strong>
                                    <strong class="total-price">Rp <span id="totalPrice">{{ number_format($grandTotal, 0, ',', '.') }}</span></strong>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="btnClearCart" style="width: 100%; margin-bottom: 0.8rem;">
                                    🗑️ Kosongkan Keranjang
                                </button>
                           <form id="checkoutForm" action="{{ route('pemesanan.checkoutPondokan') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success">✓ Checkout</button>
</form>
                            </div>
                        @else
                            <div class="empty-cart">
                                <div class="empty-cart-icon">🛒</div>
                                <p>Keranjang masih kosong</p>
                                <small>Pilih menu untuk mulai memesan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Click pada menu item untuk toggle checkbox
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if(e.target.classList.contains('menu-checkbox')) return;
                const checkbox = this.querySelector('.menu-checkbox');
                checkbox.click();
            });
        });

        // Auto add/remove ketika checkbox dicentang
        document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const tanggalAcara = document.getElementById('tanggal_acara').value;
                const alertTanggal = document.getElementById('alertTanggal');
                
                if(!tanggalAcara) {
                    alertTanggal.style.display = 'block';
                    this.checked = false;
                    setTimeout(() => {
                        alertTanggal.style.display = 'none';
                    }, 3000);
                    return;
                }

                const menuItem = this.closest('.menu-item');
                const id = this.dataset.id;

                if(this.checked) {
                    menuItem.classList.add('selected');
                    addToCart(id, tanggalAcara);
                } else {
                    menuItem.classList.remove('selected');
                    removeFromCart(id);
                }
            });
        });

        // Update qty total
        const qtyTotalInput = document.getElementById('qtyTotal');
        if(qtyTotalInput) {
            let qtyTimeout;
            qtyTotalInput.addEventListener('input', function() {
                let qty = parseInt(this.value) || 10;
                
                if(qty < 10) {
                    qty = 10;
                    this.value = 10;
                }

                clearTimeout(qtyTimeout);
                qtyTimeout = setTimeout(() => {
                    updateQtyTotal(qty);
                }, 500);
            });
        }

        function addToCart(id, tanggalAcara) {
            fetch('{{ route("pemesanan.pondokan.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_produk: id,
                    tanggal_acara: tanggalAcara
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }

        function removeFromCart(id) {
            fetch('{{ route("pemesanan.pondokan.cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id_produk: id })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }

        function updateQtyTotal(qty) {
            fetch('{{ route("pemesanan.pondokan.cart.updateQty") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ qty_total: qty })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }

        // Remove individual item
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                if(confirm('Hapus item dari keranjang?')) {
                    removeFromCart(id);
                }
            });
        });

        // Clear cart
        const btnClearCart = document.getElementById('btnClearCart');
        if(btnClearCart) {
            btnClearCart.addEventListener('click', function() {
                if(confirm('Kosongkan semua keranjang?')) {
                    fetch('{{ route("pemesanan.pondokan.cart.clear") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(() => location.reload());
                }
            });
        }
    </script>
</body>
</html>