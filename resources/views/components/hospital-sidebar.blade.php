<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="user-info">
            <div class="user-avatar">
                @php
                    $user = Auth::user();
                @endphp
                    {!! $user->avatar_html !!}
            </div>
            <div class="user-details">
                <h4>{{ auth()->user()->name }}</h4>
                <p>Hospital Administrator</p>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('hospital.dashboard') }}" class="nav-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hospital.doctors.index') }}" class="nav-link {{ request()->routeIs('hospital.doctors.*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i><span>Doctors</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hospital.appointments.index') }}" class="nav-link {{ request()->routeIs('hospital.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i><span>Appointments</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hospital.payments.index') }}" class="nav-link {{ request()->routeIs('hospital.payments.*') ? 'active' : '' }}">
                <i class="fas fa-dollar-sign"></i><span>Payments</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hospital.financial-reports') }}" class="nav-link {{ request()->routeIs('hospital.financial-reports') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Reports</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hospital.profile.show') }}" class="nav-link {{ request()->routeIs('hospital.profile.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i><span>Hospital Profile</span>
            </a>
        </div>
    </nav>
</aside>