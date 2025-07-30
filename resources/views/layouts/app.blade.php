<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FlowFinance - Gestion de vos Finances')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #1a202c;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .nav-links {
            display: flex;
            gap: 0;
            list-style: none;
            background: #f7fafc;
            padding: 4px;
            border-radius: 12px;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: #2d3748;
            background: rgba(16, 185, 129, 0.1);
        }

        .nav-links a.active {
            color: #10b981;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 12px;
            background: #f7fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-info:hover {
            background: #edf2f7;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .user-role {
            font-size: 12px;
            color: #718096;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            gap: 8px;
        }

        .btn-primary {
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .btn-outline {
            background: transparent;
            color: #10b981;
            border: 1px solid #10b981;
        }

        .btn-outline:hover {
            background: #10b981;
            color: white;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 80px);
        }

        /* Guest Layout */
        .guest-layout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .guest-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .guest-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .guest-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .guest-logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .guest-nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .guest-nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .guest-nav-links a:hover {
            color: white;
        }

        .auth-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-white {
            background: white;
            color: #4a5568;
        }

        .btn-white:hover {
            background: #f7fafc;
        }

        .btn-transparent {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-transparent:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Mobile Navigation */
        .mobile-nav-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
        }

        .mobile-nav-toggle span {
            width: 24px;
            height: 2px;
            background: #4a5568;
            margin: 2px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        .guest-layout .mobile-nav-toggle span {
            background: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links,
            .guest-nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                display: none;
                border-radius: 0 0 12px 12px;
            }

            .guest-layout .nav-links,
            .guest-layout .guest-nav-links {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }

            .nav-links.active,
            .guest-nav-links.active {
                display: flex;
            }

            .nav-links a,
            .guest-nav-links a {
                padding: 12px 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .guest-nav-links a {
                color: #4a5568;
            }

            .mobile-nav-toggle {
                display: flex;
            }

            .user-menu {
                gap: 8px;
            }

            .user-details {
                display: none;
            }

            .container {
                padding: 0 16px;
            }
        }

        /* Notification System */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notification.success {
            background: #10b981;
            color: white;
        }

        .notification.error {
            background: #ef4444;
            color: white;
        }

        .notification.info {
            background: #3b82f6;
            color: white;
        }

        .notification-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            opacity: 0.8;
        }

        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Loading States */
        .btn.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>
<body class="@guest guest-layout @endguest">
    @guest
    <!-- Guest Header -->
    <header class="guest-header">
        <div class="container">
            <nav class="guest-nav">
                <a href="{{ route('home') }}" class="guest-logo">
                    <div class="guest-logo-icon">FL</div>
                    <span>FlowFinance</span>
                </a>
                
                <ul class="guest-nav-links" id="navLinks">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="#features">Fonctionnalités</a></li>
                    <li><a href="#about">À propos</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-transparent">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-white">Inscription</a>
                </div>
                
                <div class="mobile-nav-toggle" onclick="toggleMobileNav()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>
    @else
    <!-- Authenticated Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('dashboard') }}" class="logo">
                    <div class="logo-icon">FL</div>
                    <span>FlowFinance</span>
                </a>
                
                <ul class="nav-links" id="navLinks">
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Tableau de bord</a></li>
                    <li><a href="{{ route('analytics') }}" class="{{ request()->routeIs('analytics') ? 'active' : '' }}">Analyses</a></li>
                    <li><a href="{{ route('cards') }}" class="{{ request()->routeIs('cards') ? 'active' : '' }}">Cartes</a></li>
                    <li><a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'active' : '' }}">Paramètres</a></li>
                </ul>
                
                <div class="user-menu">
                    <div class="user-info" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <div class="user-name">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                            <div class="user-role">Compte Premium</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Déconnexion</button>
                    </form>
                </div>
                
                <div class="mobile-nav-toggle" onclick="toggleMobileNav()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>
    @endguest

    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="notification success" id="notification">
                <span>{{ session('success') }}</span>
                <button class="notification-close" onclick="closeNotification()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="notification error" id="notification">
                <span>{{ session('error') }}</span>
                <button class="notification-close" onclick="closeNotification()">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Mobile Navigation Toggle
        function toggleMobileNav() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // User Menu Toggle (for future dropdown)
        function toggleUserMenu() {
            console.log('User menu clicked - implement dropdown');
        }

        // Close notification
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.remove();
            }
        }

        // Auto-close notifications
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.remove();
            }
        }, 5000);

        // Close mobile nav when clicking outside
        document.addEventListener('click', function(e) {
            const navLinks = document.getElementById('navLinks');
            const toggle = document.querySelector('.mobile-nav-toggle');
            
            if (navLinks && toggle && !toggle.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
            }
        });

        // Enhanced form submission handling
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    form.addEventListener('submit', function(e) {
                        if (!submitBtn.disabled) {
                            submitBtn.classList.add('loading');
                            submitBtn.disabled = true;
                            
                            // Reset after timeout (safety measure)
                            setTimeout(() => {
                                submitBtn.classList.remove('loading');
                                submitBtn.disabled = false;
                            }, 10000);
                        }
                    });
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>