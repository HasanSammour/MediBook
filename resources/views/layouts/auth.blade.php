<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediBook') - Medical Appointment Management System</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    
    <style>
        /* Simple Header Styles */
        .simple-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out;
        }

        .simple-header.hide {
            transform: translateY(-100%);
        }

        .simple-header.show {
            transform: translateY(0);
        }

        .simple-header .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            text-align: center;
        }

        .simple-header p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .simple-header p i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        @media (max-width: 576px) {
            .simple-header p {
                font-size: 0.7rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Simple Header - Hide on scroll down, show on scroll up -->
    <div class="simple-header">
        <div class="container">
            <p><i class="fas fa-heartbeat"></i> Welcome to MediBook - Your trusted platform for managing medical appointments online</p>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/fontawesome/js/all.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    <script>
        // Simple Header Scroll Behavior
        (function() {
            let lastScrollTop = 0;
            const header = document.querySelector('.simple-header');
            
            if (header) {
                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > lastScrollTop && scrollTop > 100) {
                        // Scrolling down - hide header
                        header.classList.add('hide');
                        header.classList.remove('show');
                    } else {
                        // Scrolling up - show header
                        header.classList.add('show');
                        header.classList.remove('hide');
                    }
                    lastScrollTop = scrollTop;
                });
            }
        })();
    </script>
    
    @stack('scripts')
</body>
</html>