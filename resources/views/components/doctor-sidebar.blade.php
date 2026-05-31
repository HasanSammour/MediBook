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
                <h4>{{ $user->name }}</h4>
                <p>{{ $user->specialization ?? 'Doctor' }}</p>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.appointments.index') }}" class="nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i><span>My Appointments</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.schedule') }}" class="nav-link {{ request()->routeIs('doctor.schedule') ? 'active' : '' }}">
                <i class="fas fa-clock"></i><span>My Schedule</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.patients.index') }}" class="nav-link {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Patient History</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('doctor.profile.edit') }}" class="nav-link {{ request()->routeIs('doctor.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profile Settings</span>
            </a>
        </div>
    </nav>
</aside>