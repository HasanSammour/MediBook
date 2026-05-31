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
                <p>Patient since {{ $user->created_at->format('Y') }}</p>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('patient.dashboard') }}" class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.appointments.index') }}" class="nav-link {{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i><span>My Appointments</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.search-doctors') }}" class="nav-link {{ request()->routeIs('patient.search-doctors') ? 'active' : '' }}">
                <i class="fas fa-search"></i><span>Find Doctors</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.search-hospitals') }}" class="nav-link {{ request()->routeIs('patient.search-hospitals') ? 'active' : '' }}">
                <i class="fas fa-hospital"></i><span>Find Hospitals</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.medical-history') }}" class="nav-link {{ request()->routeIs('patient.medical-history') ? 'active' : '' }}">
                <i class="fas fa-history"></i><span>Medical History</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('patient.profile.show') }}" class="nav-link {{ request()->routeIs('patient.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i><span>Profile Settings</span>
            </a>
        </div>
    </nav>
</aside>