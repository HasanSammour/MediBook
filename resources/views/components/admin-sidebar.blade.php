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
                <p>System Administrator</p>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.hospitals.index') }}" class="nav-link {{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
                <i class="fas fa-hospital"></i><span>Hospitals</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Users</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Reports</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i><span>Settings</span>
            </a>
        </div>
    </nav>
</aside>