<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Patient Dashboard') - MediBook</title>
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    
    @stack('styles')
</head>
<body>
    @include('components.navbar')
    @include('components.mobile-menu')
    
    <div class="dashboard-wrapper">
        @include('components.patient-sidebar')
        
        <main class="main-content">
            <div class="top-header">
                <div class="page-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', 'Welcome back, ' . Auth::user()->name)</p>
                </div>
                @yield('header-actions')
            </div>
            
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Mobile Sidebar Toggle Button -->
    <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/fontawesome/js/all.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>