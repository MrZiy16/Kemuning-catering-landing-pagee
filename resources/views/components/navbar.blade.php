<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemuning Catering Navbar</title>
    <style>
        /* ==========================================================================
           GLOBAL OVERFLOW FIX - CRITICAL
           ========================================================================== */
        
        html {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }

        body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            position: relative;
        }

        /* ==========================================================================
           NAVBAR WITH AUTHENTICATION - COMPLETE CSS (Improved for Elegance)
           ========================================================================== */

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100vw;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            z-index: 9999;
            transition: all 0.3s ease-in-out;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
            position: relative;
        }

        .nav-logo h2 {
            color: #d4a574;
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

        .nav-menu li {
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease-in-out;
            position: relative;
            padding: 10px 0;
            display: block;
            letter-spacing: 0.3px;
        }

        .nav-link:hover {
            color: #d4a574;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #d4a574;
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #d4a574;
        }

        /* ==========================================================================
           AUTH SECTION STYLES
           ========================================================================== */

        .auth-section {
            display: flex;
            align-items: center;
            margin-left: 2.5rem;
        }

        /* Login Button Styles */
        .login-btn-container {
            display: flex;
            align-items: center;
        }

        .login-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 8px 24px;
            background: linear-gradient(135deg, #d4a574 0%, #c49660 100%);
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

        .login-icon {
            font-size: 1.1rem;
        }

        .login-text {
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        /* User Menu Styles */
        .user-menu-container {
            position: relative;
        }

        .user-menu-trigger {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(212, 165, 116, 0.2);
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            min-width: 150px;
        }

        .user-menu-trigger:hover {
            background: rgba(255, 255, 255, 1);
            border-color: rgba(212, 165, 116, 0.4);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .user-menu-trigger.active {
            background: rgba(255, 255, 255, 1);
            border-color: #d4a574;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4a574 0%, #c49660 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-text {
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: 0.2px;
        }

        .user-role {
            font-size: 0.8rem;
            color: #666;
            letter-spacing: 0.2px;
        }

        .dropdown-arrow {
            color: #666;
            font-size: 0.75rem;
            transition: transform 0.3s ease-in-out;
        }

        .user-menu-trigger.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Dropdown Menu Styles */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(0, 0, 0, 0.08);
            min-width: 280px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
            z-index: 1001;
            overflow: hidden;
        }

        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px 16px 0 0;
        }

        .user-avatar-large {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4a574 0%, #c49660 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-text-large {
            color: white;
            font-weight: 600;
            font-size: 1.15rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .user-name-large {
            font-weight: 600;
            color: #333;
            font-size: 1.05rem;
            margin-bottom: 0.25rem;
            letter-spacing: 0.2px;
        }

        .user-email {
            font-size: 0.85rem;
            color: #666;
            word-break: break-all;
            letter-spacing: 0.1px;
        }

        .dropdown-menu {
            padding: 0.75rem 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.85rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
        }

        .dropdown-item:hover {
            background: rgba(212, 165, 116, 0.08);
            color: #d4a574;
        }

        .dropdown-item.logout-item:hover {
            background: rgba(220, 53, 69, 0.08);
            color: #dc3545;
        }

        .dropdown-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.08);
            margin: 0.5rem 0;
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
            background: #333;
            margin: 3px 0;
            transition: all 0.3s ease-in-out;
            display: block;
            border-radius: 2px;
        }

        /* ==========================================================================
           RESPONSIVE DESIGN - CRITICAL FIX FOR MOBILE MENU
           ========================================================================== */

        @media screen and (max-width: 1024px) {
            .auth-section {
                margin-left: 1.5rem;
            }

            .user-menu-trigger {
                min-width: 130px;
            }

            .user-name {
                font-size: 0.9rem;
            }

            .dropdown-header {
                padding: 1.25rem;
            }

            .user-dropdown {
                min-width: 260px;
            }
        }

        @media screen and (max-width: 768px) {
            /* Show hamburger menu */
            .hamburger {
                display: flex;
                order: 3;
                margin-left: 1rem;
            }

            /* Auth section adjustments */
            .auth-section {
                order: 2;
                margin-left: auto;
                margin-right: 1rem;
            }

            /* Compact login button on mobile */
            .login-btn {
                padding: 6px 16px;
                border-radius: 25px;
            }

            .login-text {
                display: none;
            }

            .login-icon {
                font-size: 1.2rem;
            }

            /* Compact user menu on mobile */
            .user-menu-trigger {
                min-width: auto;
                padding: 4px 8px;
                gap: 0.5rem;
            }

            .user-info {
                display: none;
            }

            .user-avatar {
                width: 28px;
                height: 28px;
            }

            .avatar-text {
                font-size: 0.75rem;
            }

            .dropdown-arrow {
                display: none;
            }

            /* Adjust dropdown position */
            .user-dropdown {
                right: -10px;
                min-width: 250px;
            }

            /* CRITICAL FIX: Mobile menu positioning */
            .nav-menu {
                position: fixed;
                left: -100%;
                top: 72px;
                flex-direction: column;
                background: rgba(255, 255, 255, 0.98);
                width: 100%;
                max-width: 100vw;
                text-align: center;
                transition: left 0.3s ease-in-out;
                padding: 2rem 0;
                backdrop-filter: blur(10px);
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                gap: 0;
                order: 4;
                height: calc(100vh - 72px);
                overflow-y: auto;
                z-index: 9998;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-menu li {
                margin: 1.25rem 0;
            }

            .nav-link {
                color: #333;
                font-size: 1.1rem;
                padding: 1rem 0;
            }

            .nav-link::after {
                bottom: 0.5rem;
            }

            /* Hamburger animation when active */
            .hamburger.active span:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }

            .hamburger.active span:nth-child(2) {
                opacity: 0;
            }

            .hamburger.active span:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }
        }

        @media screen and (max-width: 480px) {
            .nav-container {
                padding: 0 15px;
                height: 60px;
            }

            .nav-logo h2 {
                font-size: 1.3rem;
            }

            .nav-menu {
                top: 60px;
                padding: 1.5rem 0;
            }

            .nav-link {
                font-size: 1rem;
                padding: 0.8rem 0;
            }

            .hamburger {
                padding: 3px;
            }

            .hamburger span {
                width: 22px;
                height: 2.5px;
                margin: 2.5px 0;
            }

            .user-dropdown {
                right: -15px;
                min-width: 220px;
            }

            .dropdown-header {
                padding: 1rem;
            }

            .user-avatar-large {
                width: 40px;
                height: 40px;
            }

            .avatar-text-large {
                font-size: 1rem;
            }
        }

        /* ==========================================================================
           ACCESSIBILITY & ANIMATIONS
           ========================================================================== */

        /* Focus states for accessibility */
        .login-btn:focus,
        .user-menu-trigger:focus,
        .dropdown-item:focus,
        .hamburger:focus {
            outline: 2px solid #d4a574;
            outline-offset: 2px;
        }

        .hamburger:focus {
            border-radius: 4px;
        }

        /* Prevent body scroll when mobile menu is open */
        body.menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .login-btn {
                background: #8B4513;
                border: 2px solid #000;
            }

            .user-menu-trigger {
                border: 2px solid #000;
                background: #fff;
            }

            .user-dropdown {
                border: 2px solid #000;
            }

            .dropdown-item:hover {
                background: #f0f0f0;
                color: #000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Component -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="{{ url('/') }}"><h2>Kemuning Catering</h2></a>
            </div>
            
            <!-- Menu -->
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#bestseller" class="nav-link">Best Seller</a></li>
                <li><a href="#testimonial" class="nav-link">Testimonial</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <!-- Auth Section -->
            <div class="auth-section">
                @guest
                    <!-- Login Button -->
                    <div class="login-btn-container">
                        <a href="{{ route('login') }}" class="login-btn">
                            <span class="login-icon">👤</span>
                            <span class="login-text">Login</span>
                        </a>
                    </div>
                @endguest

                @auth
                    <!-- User Menu -->
                    <div class="user-menu-container">
                        <div class="user-menu-trigger" id="userMenuTrigger">
                            <div class="user-avatar">
                                <span class="avatar-text">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</span>
                            </div>
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->nama }}</span>
                                <span class="user-role">{{ ucfirst(Auth::user()->peran) }}</span>
                            </div>
                            <div class="dropdown-arrow">
                                <span>▼</span>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="user-dropdown" id="userDropdown">
                            <div class="dropdown-header">
                                <div class="user-avatar-large">
                                    <span class="avatar-text-large">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</span>
                                </div>
                                <div class="user-details">
                                    <span class="user-name-large">{{ Auth::user()->nama }}</span>
                                    <span class="user-email">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-menu">
                                @if(Auth::user()->role === 'admin'|| Auth::user()->role === 'super_admin')
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                    <span class="dropdown-icon">📊</span>
                                    <span>Dashboard</span>
                                </a>
                                @endif
                                <a href="{{ route('pesanan.index') }}" class="dropdown-item">
                                    <span class="dropdown-icon">📋</span>
                                    <span>Pesanan Saya</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <span class="dropdown-icon">⚙️</span>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <span class="dropdown-icon">🚪</span>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Hamburger for mobile -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuTrigger = document.getElementById('userMenuTrigger');
            const userDropdown = document.getElementById('userDropdown');
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('nav-menu');
            const body = document.body;

            if (userMenuTrigger) {
                userMenuTrigger.addEventListener('click', function(event) {
                    event.stopPropagation();
                    userDropdown.classList.toggle('active');
                    userMenuTrigger.classList.toggle('active');
                });

                document.addEventListener('click', function(event) {
                    if (!userMenuTrigger.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.remove('active');
                        userMenuTrigger.classList.remove('active');
                    }
                });
            }

            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
                body.classList.toggle('menu-open');
            });
        });
    </script>
</body>
</html>