<div class="mobile-menu" id="mobileMenu">
    <ul class="mobile-nav-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('features') }}">Features</a></li>
        <li><a href="{{ route('hospitals') }}">Hospitals</a></li>
        <li><a href="{{ route('doctors') }}">Doctors</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
    <div class="mobile-nav-buttons">
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
               onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" 
               class="btn btn-outline">Logout</a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        @endauth
    </div>
</div>