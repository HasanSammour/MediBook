<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="MediBook Logo" class="logo">
                <span class="brand-text">MediBook</span>
            </a>
        </div>
        <div class="nav-menu">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('features') }}">Features</a></li>
                <li><a href="{{ route('hospitals') }}">Hospitals</a></li>
                <li><a href="{{ route('doctors') }}">Doctors</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                @auth
                    @php
                        $role = auth()->user()->roles->first()->name ?? 'patient';
                        $dashboardRoute = match($role) {
                            'system_admin' => 'admin.dashboard',
                            'hospital_admin' => 'hospital.dashboard',
                            'doctor' => 'doctor.dashboard',
                            default => 'patient.dashboard'
                        };
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="btn btn-outline">Dashboard</a>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();" 
                       class="btn btn-outline">Logout</a>
                    <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endauth
            </div>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>