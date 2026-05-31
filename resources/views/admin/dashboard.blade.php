{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name)

@push('styles')
    <style>
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .stat-info h4 {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        /* Loading Skeletons for Stats */
        .stat-skeleton {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .skeleton-info {
            flex: 1;
        }
        
        .skeleton-line-sm {
            height: 10px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            margin-bottom: 8px;
            width: 60%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-line-lg {
            height: 24px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            width: 70%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Full Width Chart Card */
        .chart-card-full {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .chart-card-full h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .chart-card-full h3 i {
            color: var(--primary-color);
        }
        
        .chart-container-full {
            height: 380px;
            position: relative;
        }
        
        /* Two Columns Row */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .chart-card-half {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .chart-card-half h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .chart-card-half h3 i {
            color: var(--primary-color);
        }
        
        .chart-container-half {
            height: 320px;
            position: relative;
        }
        
        canvas {
            max-height: 300px;
            width: 100% !important;
        }
        
        .chart-skeleton-full {
            height: 350px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        .chart-skeleton-half {
            height: 280px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        /* Recent Hospitals Table */
        .recent-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .card-header h3 {
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .card-header a {
            font-size: 0.75rem;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        /* Circle Spinner Loading */
        .loading-spinner-container {
            text-align: center;
            padding: 40px;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 10px;
            color: var(--gray-color);
            font-size: 0.85rem;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .recent-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }
        
        .recent-table th,
        .recent-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
        }
        
        .recent-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .empty-state p {
            color: var(--gray-color);
            font-size: 0.85rem;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        
        .action-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-light);
        }
        
        .action-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }
        
        .action-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .action-card h4 {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            color: var(--dark-color);
        }
        
        .action-card p {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .charts-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        
            .stat-value {
                font-size: 1.2rem;
            }
            
            .chart-container-full {
                height: 300px;
            }
            
            .chart-container-half {
                height: 280px;
            }
        }
        
        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Stats Cards with Loading Skeletons -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
    </div>

    <!-- Row 1: Appointments by Hospital (Full Width) -->
    <div class="chart-card-full">
        <h3><i class="fas fa-chart-bar"></i> Appointments by Hospital</h3>
        <div class="chart-container-full">
            <div id="appointmentsSkeleton" class="chart-skeleton-full"></div>
            <canvas id="appointmentsChart" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Row 2: Two Columns (Users Distribution + Monthly Trend) -->
    <div class="charts-row">
        <div class="chart-card-half">
            <h3><i class="fas fa-chart-pie"></i> Users Distribution</h3>
            <div class="chart-container-half">
                <div id="usersSkeleton" class="chart-skeleton-half"></div>
                <canvas id="usersChart" style="display: none;"></canvas>
            </div>
        </div>
        <div class="chart-card-half">
            <h3><i class="fas fa-chart-line"></i> Monthly Appointments Trend</h3>
            <div class="chart-container-half">
                <div id="trendSkeleton" class="chart-skeleton-half"></div>
                <canvas id="trendChart" style="display: none;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Hospitals Table -->
    <div class="recent-card">
        <div class="card-header">
            <h3><i class="fas fa-hospital"></i> Recently Added Hospitals</h3>
            <a href="{{ route('admin.hospitals.index') }}">View All →</a>
        </div>
        <div id="recentHospitalsContainer">
            <!-- Circle Spinner Loading -->
            <div class="loading-spinner-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading hospitals...</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('admin.hospitals.create') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
            <h4>Add Hospital</h4>
            <p>Register new hospital</p>
        </a>
        <a href="{{ route('admin.hospitals.index') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-edit"></i></div>
            <h4>Manage Hospitals</h4>
            <p>Edit or deactivate</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-user-cog"></i></div>
            <h4>Manage Users</h4>
            <p>Activate/Deactivate accounts</p>
        </a>
        <a href="{{ route('admin.reports') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-chart-line"></i></div>
            <h4>View Reports</h4>
            <p>Platform analytics</p>
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let appointmentsChart = null;
        let usersChart = null;
        let trendChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadAppointmentsByHospital();
            loadUsersDistribution();
            loadMonthlyTrend();
            loadRecentHospitals();
        });

        async function loadStats() {
            const statsGrid = document.getElementById('statsGrid');

            try {
                const response = await fetch('{{ route("admin.dashboard.stats") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    statsGrid.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4>Total Hospitals</h4>
                                <div class="stat-value">${data.stats.total_hospitals.toLocaleString()}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-hospital"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4>Total Doctors</h4>
                                <div class="stat-value">${data.stats.total_doctors.toLocaleString()}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4>Total Patients</h4>
                                <div class="stat-value">${data.stats.total_patients.toLocaleString()}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4>Total Appointments</h4>
                                <div class="stat-value">${data.stats.total_appointments.toLocaleString()}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function loadAppointmentsByHospital() {
            const skeleton = document.getElementById('appointmentsSkeleton');
            const canvas = document.getElementById('appointmentsChart');

            try {
                const response = await fetch('{{ route("admin.dashboard.appointments-by-hospital") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels.length > 0) {
                    skeleton.style.display = 'none';
                    canvas.style.display = 'block';

                    if (appointmentsChart) appointmentsChart.destroy();

                    const ctx = canvas.getContext('2d');
                    appointmentsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Appointments',
                                data: data.values,
                                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                } else {
                    skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-simple"></i><p>No data available</p></div>';
                }
            } catch (error) {
                console.error('Error loading appointments chart:', error);
                skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-simple"></i><p>Failed to load chart</p></div>';
            }
        }

        async function loadUsersDistribution() {
            const skeleton = document.getElementById('usersSkeleton');
            const canvas = document.getElementById('usersChart');

            try {
                const response = await fetch('{{ route("admin.dashboard.users-distribution") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels.length > 0) {
                    skeleton.style.display = 'none';
                    canvas.style.display = 'block';

                    if (usersChart) usersChart.destroy();

                    const ctx = canvas.getContext('2d');
                    usersChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: data.colors,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                } else {
                    skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-pie"></i><p>No data available</p></div>';
                }
            } catch (error) {
                console.error('Error loading users chart:', error);
                skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-pie"></i><p>Failed to load chart</p></div>';
            }
        }

        async function loadMonthlyTrend() {
            const skeleton = document.getElementById('trendSkeleton');
            const canvas = document.getElementById('trendChart');

            try {
                const response = await fetch('{{ route("admin.dashboard.monthly-trend") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels.length > 0) {
                    skeleton.style.display = 'none';
                    canvas.style.display = 'block';

                    if (trendChart) trendChart.destroy();

                    const ctx = canvas.getContext('2d');
                    trendChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Appointments',
                                data: data.values,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                } else {
                    skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-line"></i><p>No data available</p></div>';
                }
            } catch (error) {
                console.error('Error loading trend chart:', error);
                skeleton.innerHTML = '<div class="empty-state"><i class="fas fa-chart-line"></i><p>Failed to load chart</p></div>';
            }
        }

        async function loadRecentHospitals() {
            const container = document.getElementById('recentHospitalsContainer');

            // Show circle spinner
            container.innerHTML = `
                <div class="loading-spinner-container">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading hospitals...</div>
                </div>
            `;

            try {
                const response = await fetch('{{ route("admin.dashboard.recent-hospitals") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.hospitals.length > 0) {
                    let html = `
                        <div class="table-container">
                            <table class="recent-table">
                                <thead>
                                    <tr>
                                        <th>Hospital Name</th>
                                        <th>Location</th>
                                        <th>Doctors</th>
                                        <th>Status</th>
                                        <th>Added Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    data.hospitals.forEach(hospital => {
                        html += `
                            <tr>
                                <td><strong>${escapeHtml(hospital.name)}</strong></td>
                                <td>${escapeHtml(hospital.location)}</td>
                                <td>${hospital.doctors_count}</td>
                                <td><span class="status-badge ${hospital.status === 'active' ? 'status-active' : 'status-inactive'}">${hospital.status === 'active' ? 'Active' : 'Inactive'}</span></td>
                                <td>${hospital.created_at}</td>
                            </tr>
                        `;
                    });

                    html += `</tbody>赶</div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-hospital"></i><p>No hospitals found</p></div>';
                }
            } catch (error) {
                console.error('Error loading recent hospitals:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Failed to load hospitals</p></div>';
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
    </script>
@endpush