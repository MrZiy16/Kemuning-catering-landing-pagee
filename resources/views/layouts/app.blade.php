<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            overflow-x: hidden;
            background: #f8fafc;
        }

        /* Enhanced Sidebar styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        /* Brand Section */
        .sidebar .brand-section {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .sidebar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar .navbar-brand:hover {
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .navbar-brand i {
            font-size: 2rem;
            margin-right: 15px;
            background: linear-gradient(45deg, #ffd700, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navigation Menu */
        .sidebar .nav-menu {
            padding: 20px 0;
            flex: 1;
        }

        .sidebar .nav-item {
            margin: 0 15px 8px 15px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-left: 4px solid #ffd700;
        }

        .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: #ffd700;
            border-radius: 50%;
            box-shadow: 0 0 10px #ffd700;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        /* User Profile Section (Optional) */
        .sidebar .user-section {
            padding: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar .user-info {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .sidebar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ffd700, #ff6b6b);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 600;
            color: #fff;
        }

        /* Main content area */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 100vh;
        }

        /* Toggle button for mobile */
        .sidebar-toggle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 15px;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(102, 126, 234, 0.6);
        }

        /* Responsive: hide sidebar on small screens */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-280px);
                width: 280px;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }

        /* Enhanced Card & Table styling */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 16px 16px 0 0;
        }

        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-toggle {
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-group .btn {
            margin: 0 3px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* Alert enhancements */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        /* Page header styling */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
    </style>
</head>
<body>

    <!-- Enhanced Sidebar -->
    <nav class="sidebar d-flex flex-column" id="sidebar">
        <!-- Brand Section -->
        <div class="brand-section">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-utensils"></i>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 700;">Catering</div>
                    <div style="font-size: 0.8rem; opacity: 0.8; margin-top: -5px;">Admin Panel</div>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="nav-menu flex-grow-1">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}" 
                       href="{{ route('admin.menu.index') }}">
                        <i class="fas fa-list"></i>
                        Master Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}" 
                       href="{{ route('admin.produk.index') }}">
                        <i class="fas fa-box"></i>
                        Produk
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->role === 'super_admin')
                <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                Users
                </a>
                </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" 
                       href="{{ route('admin.customers.index') }}">
                        <i class="fas fa-users"></i>
                        Customers 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}" 
                       href="{{ route('admin.transaksi.index') }}">
                        <i class="fas fa-receipt"></i>
                        Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" 
                       href="{{ route('admin.payments.index') }}">
                        <i class="fas fa-credit-card"></i>
                        Pembayaran
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->role === 'super_admin')
                <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" 
                href="{{ route('admin.laporan.index') }}">
                <i class="fas fa-chart-bar"></i>
                Laporan
                </a>
                </li>
                @endif
            </ul>
        </div>

        <!-- User Section (Optional) -->
        <div class="user-section">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div style="font-weight: 600;">Admin</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">Administrator</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Enhanced Toggle Button for Mobile -->
    <button class="sidebar-toggle d-md-none position-fixed top-0 start-0 m-3" 
            style="z-index: 1001;"
            type="button" 
            onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Enhanced Toggle Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const button = document.querySelector('.sidebar-toggle');
            if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && !button.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Add smooth scrolling
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        document.getElementById('sidebar').classList.remove('show');
                    }, 150);
                }
            });
        });
    </script>

    <!-- Delete Confirmation Script -->
    <script>
        function confirmDelete(form) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                form.submit();
            }
        }

        // Toggle Status with AJAX
        function toggleStatus(menuId) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post(`/admin/menu/${menuId}/toggle-status`)
                .done(function(data) {
                    if (data.success) {
                        const badge = $(`#status-${menuId}`);
                        if (data.status === 'active') {
                            badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                        }
                    }
                })
                .fail(function() {
                    alert('Terjadi kesalahan saat mengubah status');
                });
        }
    </script>

    @stack('scripts')
</body>
</html>