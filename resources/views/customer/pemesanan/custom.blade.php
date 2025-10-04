<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Custom Menu - Kemuning Catering</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gold: #d4a574;
            --dark-gold: #b8935f;
            --accent-brown: #8B4513;
            --text-dark: #2c2c2c;
            --text-light: #666;
            --bg-light: #fafafa;
            --white: #ffffff;
            --shadow-light: rgba(0,0,0,0.08);
            --shadow-medium: rgba(0,0,0,0.15);
            --gradient: linear-gradient(135deg, #d4a574 0%, #c49660 100%);
            --gradient-hover: linear-gradient(135deg, #c49660 0%, #b8935f 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .cart-toggle {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--gradient);
            border: none;
            padding: 1rem;
            border-radius: 50%;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            box-shadow: 0 8px 25px rgba(212,165,116,0.3);
            z-index: 100;
        }

        .cart-toggle:hover {
            background: var(--gradient-hover);
            transform: scale(1.1);
            box-shadow: 0 12px 30px rgba(212,165,116,0.4);
        }

        .cart-toggle i {
            font-size: 1.5rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 50%;
            font-size: 0.75rem;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-title p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .menu-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px var(--shadow-light);
            transition: all 0.3s ease;
            position: relative;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px var(--shadow-medium);
        }

        .menu-image {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .menu-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .menu-card:hover .menu-image img {
            transform: scale(1.05);
        }

        .category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(212,165,116,0.9);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .menu-content {
            padding: 1.5rem;
        }

        .menu-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .menu-description {
            font-size: 0.95rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .menu-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-gold);
            margin-bottom: 1.5rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .quantity-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
            min-width: 60px;
        }

        .quantity-input {
            display: flex;
            align-items: center;
            border: 2px solid #e1e1e1;
            border-radius: 12px;
            background: var(--white);
            overflow: hidden;
        }

        .qty-btn {
            background: none;
            border: none;
            padding: 0.5rem 0.75rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text-light);
        }

        .qty-btn:hover {
            background: var(--bg-light);
            color: var(--primary-gold);
        }

        .qty-input {
            border: none;
            padding: 0.5rem;
            width: 50px;
            text-align: center;
            font-weight: 500;
            background: none;
            color: var(--text-dark);
        }

        .add-to-cart-btn {
            width: 100%;
            background: var(--gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .add-to-cart-btn:hover {
            background: var(--gradient-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,165,116,0.3);
        }

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        .add-to-cart-btn.added {
            background: #28a745;
            animation: pulse 0.6s ease;
        }

        .add-to-cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: var(--white);
            box-shadow: -5px 0 30px var(--shadow-medium);
            transition: right 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-header {
            padding: 2rem;
            border-bottom: 1px solid #e1e1e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .close-cart {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.2s ease;
        }

        .close-cart:hover {
            color: var(--text-dark);
        }

        .cart-content {
            flex: 1;
            padding: 1rem 2rem;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .cart-item-price {
            color: var(--primary-gold);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-qty-btn {
            background: var(--bg-light);
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .cart-qty-btn:hover {
            background: var(--primary-gold);
            color: white;
        }

        .cart-qty {
            font-size: 0.9rem;
            font-weight: 500;
            min-width: 20px;
            text-align: center;
        }

        .remove-item {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .remove-item:hover {
            background: #c82333;
        }

        .cart-footer {
            padding: 2rem;
            border-top: 1px solid #e1e1e1;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .cart-total-price {
            color: var(--primary-gold);
        }

        .checkout-btn {
            width: 100%;
            background: var(--gradient);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .checkout-btn:hover {
            background: var(--gradient-hover);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .cart-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem 0;
            color: var(--text-light);
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-gold);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            
            .container {
                padding: 2rem 1rem;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .page-title h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    
    <!-- Floating Cart Button -->
    <button class="cart-toggle" onclick="toggleCart()">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count" id="cartCount">0</span>
    </button>

    <div class="container">
        <div class="page-title">
            <h1>Buat Pesanan Custom Menu</h1>
            <p>Pilih menu favorit Anda dan buat kombinasi yang sempurna untuk acara istimewa</p>
        </div>

        <div class="menu-grid" id="menuGrid">
            @foreach($menu as $item)
            <div class="menu-card">
                <div class="menu-image">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&h=300&fit=crop" alt="{{ $item->nama_menu }}">
                    @endif
                    <div class="category-badge">
                        {{ ucwords(str_replace('_', ' ', $item->kategori_menu)) }}
                    </div>
                </div>
                
                <div class="menu-content">
                    <h4 class="menu-title">{{ $item->nama_menu }}</h4>
                    <p class="menu-description">{{ $item->deskripsi ?? 'Deskripsi menu belum tersedia' }}</p>
                    <p class="menu-price">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                    
                    <div class="quantity-control">
                        <span class="quantity-label">Jumlah:</span>
                        <div class="quantity-input">
                            <button class="qty-btn" type="button" onclick="decreaseQty({{ $item->id_menu }})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="qty-input" id="qty-{{ $item->id_menu }}" value="1" min="1" onchange="validateQty({{ $item->id_menu }})" oninput="validateQty({{ $item->id_menu }})")
                            <button class="qty-btn" type="button" onclick="increaseQty({{ $item->id_menu }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button class="add-to-cart-btn" id="btn-{{ $item->id_menu }}" onclick="addToCart({{ $item->id_menu }}, '{{ $item->nama_menu }}', {{ $item->harga_satuan }})">
                        <i class="fas fa-cart-plus"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3 class="cart-title">Keranjang Belanja</h3>
            <button class="close-cart" onclick="toggleCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cart-content" id="cartContent">
            <div class="empty-cart">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Keranjang masih kosong</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span class="cart-total-price" id="cartTotalPrice">Rp 0</span>
            </div>
            
            <!-- Form tanggal acara -->
            <div class="date-form" id="dateForm" style="display: none;">
                <label for="tanggalAcara" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark);">Tanggal Acara:</label>
                <input type="date" id="tanggalAcara" style="width: 100%; padding: 0.75rem; border: 1px solid #e1e1e1; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;" min="">
            </div>
            
            <button class="checkout-btn" id="checkoutBtn" disabled onclick="goToCheckout()">
                Lanjut ke Pembayaran
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Item berhasil ditambahkan ke keranjang!</span>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Global variables
        var cart = {};
        var isCartOpen = false;

        // Load cart from server on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize empty cart first
            updateCartDisplay();
            loadCartFromServer();
        });

        function loadCartFromServer() {
            fetch('/pemesanan/custom/get-cart', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart || {};
                    updateCartDisplay();
                }
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                cart = {};
                updateCartDisplay();
            });
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function increaseQty(menuId) {
            const qtyInput = document.getElementById(`qty-${menuId}`);
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty(menuId) {
            const qtyInput = document.getElementById(`qty-${menuId}`);
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        function validateQty(menuId) {
            const qtyInput = document.getElementById(`qty-${menuId}`);
            let value = parseInt(qtyInput.value) || 1;
            
            // Ensure minimum value is 1
            if (value < 1 || isNaN(value)) {
                value = 1;
            }
            
            // Set maximum to 999 to prevent extreme values
            if (value > 999) {
                value = 999;
                showToast('Maksimal pemesanan 999 porsi per menu');
            }
            
            qtyInput.value = value;
        }

        function addToCart(menuId, namaMenu, harga) {
            var qtyInput = document.getElementById(`qty-${menuId}`);
            var qty = parseInt(qtyInput.value);
            var btn = document.getElementById(`btn-${menuId}`);
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
            
            // Send AJAX request to add item to cart
            fetch('/pemesanan/custom/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_menu: menuId,
                    nama_menu: namaMenu,
                    harga: harga,
                    qty: qty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart || {};
                    updateCartDisplay();
                    
                    // Reset quantity to 1
                    qtyInput.value = 1;
                    
                    // Button success animation
                    btn.classList.add('added');
                    btn.innerHTML = '<i class="fas fa-check"></i> Ditambahkan!';
                    
                    showToast(data.message);
                    
                    setTimeout(function() {
                        btn.classList.remove('added');
                        btn.innerHTML = '<i class="fas fa-cart-plus"></i> Tambah ke Keranjang';
                        btn.disabled = false;
                    }, 2000);
                } else {
                    showToast('Gagal menambahkan item ke keranjang');
                    btn.innerHTML = '<i class="fas fa-cart-plus"></i> Tambah ke Keranjang';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan');
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> Tambah ke Keranjang';
                btn.disabled = false;
            });
        }

        function updateCartQty(menuId, newQty) {
            if (newQty < 1) {
                removeFromCart(menuId);
                return;
            }
            
            fetch('/pemesanan/custom/update-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_menu: menuId,
                    qty: newQty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();
                } else {
                    showToast('Gagal mengupdate keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan');
            });
        }

        function removeFromCart(menuId) {
            fetch('/pemesanan/custom/remove-from-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_menu: menuId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();
                    showToast('Item dihapus dari keranjang');
                } else {
                    showToast('Gagal menghapus item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan');
            });
        }

        function updateCartDisplay() {
            const cartContent = document.getElementById('cartContent');
            const cartCount = document.getElementById('cartCount');
            const cartTotalPrice = document.getElementById('cartTotalPrice');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const dateForm = document.getElementById('dateForm');
            
            // Ensure cart is initialized
            if (!cart || typeof cart !== 'object') {
                cart = {};
            }
            
            const cartItems = Object.values(cart);
            const totalItems = cartItems.reduce((sum, item) => sum + parseInt(item.qty || 0), 0);
            const totalPrice = cartItems.reduce((sum, item) => sum + (parseInt(item.qty || 0) * parseFloat(item.harga || 0)), 0);
            
            cartCount.textContent = totalItems;
            cartTotalPrice.textContent = formatRupiah(totalPrice);
            
            if (cartItems.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <p>Keranjang masih kosong</p>
                    </div>
                `;
                checkoutBtn.disabled = true;
                dateForm.style.display = 'none';
            } else {
                cartContent.innerHTML = cartItems.map(item => `
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.nama_menu || 'Menu'}</div>
                            <div class="cart-item-price">${formatRupiah(item.harga || 0)} × ${item.qty || 0} = ${formatRupiah((item.harga || 0) * (item.qty || 0))}</div>
                        </div>
                        <div class="cart-item-qty">
                            <button class="cart-qty-btn" onclick="updateCartQty(${item.id_menu}, ${parseInt(item.qty || 0) - 1})">-</button>
                            <span class="cart-qty">${item.qty || 0}</span>
                            <button class="cart-qty-btn" onclick="updateCartQty(${item.id_menu}, ${parseInt(item.qty || 0) + 1})">+</button>
                            <button class="remove-item" onclick="removeFromCart(${item.id_menu})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
                checkoutBtn.disabled = false;
                dateForm.style.display = 'block';
                
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('tanggalAcara').min = today;
            }
        }

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            const cartOverlay = document.getElementById('cartOverlay');
            
            isCartOpen = !isCartOpen;
            
            if (isCartOpen) {
                cartSidebar.classList.add('open');
                cartOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        function goToCheckout() {
            if (Object.keys(cart).length === 0) {
                showToast('Keranjang kosong!');
                return;
            }
            
            const tanggalAcara = document.getElementById('tanggalAcara').value;
            if (!tanggalAcara) {
                showToast('Pilih tanggal acara terlebih dahulu!');
                document.getElementById('tanggalAcara').focus();
                return;
            }
            
            // Send checkout request with date
            fetch('/pemesanan/custom/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    tanggal_acara: tanggalAcara
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to form pengiriman
                    window.location.href = '/pemesanan/pengiriman';
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat checkout');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat checkout');
            });
        }

        // Close cart when clicking outside
        document.addEventListener('click', function(e) {
            if (isCartOpen && !e.target.closest('.cart-sidebar') && !e.target.closest('.cart-toggle')) {
                toggleCart();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isCartOpen) {
                toggleCart();
            }
        });
    </script>

    <!-- Include jQuery for AJAX (if not already included) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>