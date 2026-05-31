@extends('layouts.hospital')

@section('title', 'Hospital Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name)

@push('styles')
    <style>
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        /* Loading Skeleton for Stats */
        .stat-skeleton {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
        }

        .skeleton-icon {
            width: 45px;
            height: 45px;
            background: #e5e7eb;
            border-radius: 12px;
            margin-bottom: 1rem;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .skeleton-line {
            height: 28px;
            background: #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            width: 70%;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .skeleton-line-small {
            height: 14px;
            background: #e5e7eb;
            border-radius: 8px;
            width: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .chart-card h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            height: 280px;
            position: relative;
        }

        /* Chart Loading */
        .chart-skeleton {
            height: 250px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 12px;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Today's Appointments */
        .appointments-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
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

        .appointments-table-container {
            overflow-x: auto;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        .appointments-table th,
        .appointments-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
        }

        .appointments-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .patient-avatar-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .patient-avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 40px;
        }

        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 10px;
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        /* Skeleton Row for Table */
        .skeleton-row {
            display: flex;
            gap: 1rem;
            padding: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .skeleton-cell {
            height: 20px;
            background: #e5e7eb;
            border-radius: 6px;
            animation: pulse 1.5s ease-in-out infinite;
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
                font-size: 1.5rem;
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
    <!-- Stats Cards with Skeleton Loading -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
    </div>

    <!-- Charts Row with Chart Loading -->
    <div class="charts-row">
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Appointments by Doctor</h3>
            <div class="chart-container" id="appointmentsChartContainer">
                <div class="chart-skeleton"></div>
            </div>
            <canvas id="appointmentsChart" style="display: none;"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Revenue Trend</h3>
            <div class="chart-container" id="revenueChartContainer">
                <div class="chart-skeleton"></div>
            </div>
            <canvas id="revenueChart" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Today's Appointments with Loading Spinner -->
    <div class="appointments-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-today"></i> Today's Appointments</h3>
            <a href="{{ route('hospital.appointments.index') }}">View All →</a>
        </div>
        <div id="todayAppointments">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading appointments...</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('hospital.doctors.index') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-user-plus"></i></div>
            <h4>Add Doctor</h4>
            <p>Add new doctor to hospital</p>
        </a>
        <a href="{{ route('hospital.appointments.index') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-calendar-plus"></i></div>
            <h4>Manage Appointments</h4>
            <p>View all appointments</p>
        </a>
        <a href="{{ route('hospital.payments.index') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-receipt"></i></div>
            <h4>Record Payment</h4>
            <p>Record patient payment</p>
        </a>
        <a href="{{ route('hospital.profile.edit') }}" class="action-card">
            <div class="action-icon"><i class="fas fa-edit"></i></div>
            <h4>Edit Profile</h4>
            <p>Update hospital info</p>
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let appointmentsChart = null;
        let revenueChart = null;

        document.addEventListener('DOMContentLoaded', function () {
            loadStats();
            loadAppointmentsByDoctor();
            loadRevenueTrend();
            loadTodayAppointments();
        });

        async function loadStats() {
            const statsGrid = document.getElementById('statsGrid');

            try {
                const response = await fetch('{{ route("hospital.dashboard.stats") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    const stats = data.stats;
                    statsGrid.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                        <div class="stat-value">${stats.total_doctors}</div>
                        <div class="stat-label">Total Doctors</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="stat-value">${stats.today_appointments}</div>
                        <div class="stat-label">Today's Appointments</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-value">$${stats.monthly_revenue.toLocaleString()}</div>
                        <div class="stat-label">Monthly Revenue</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-value">${stats.total_patients.toLocaleString()}</div>
                        <div class="stat-label">Total Patients</div>
                    </div>
                `;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
                statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="stat-value">Error</div>
                    <div class="stat-label">Failed to load</div>
                </div>
            `.repeat(4);
            }
        }

        async function loadAppointmentsByDoctor() {
            const chartContainer = document.getElementById('appointmentsChartContainer');
            const canvas = document.getElementById('appointmentsChart');

            try {
                const response = await fetch('{{ route("hospital.dashboard.appointments-by-doctor") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    // Hide skeleton, show canvas
                    chartContainer.style.display = 'none';
                    canvas.style.display = 'block';

                    const ctx = canvas.getContext('2d');

                    if (appointmentsChart) appointmentsChart.destroy();

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
                }
            } catch (error) {
                console.error('Error loading appointments chart:', error);
                chartContainer.innerHTML = '<div class="empty-state" style="padding: 40px;"><i class="fas fa-chart-simple"></i><p>Failed to load chart</p></div>';
            }
        }

        async function loadRevenueTrend() {
            const chartContainer = document.getElementById('revenueChartContainer');
            const canvas = document.getElementById('revenueChart');

            try {
                const response = await fetch('{{ route("hospital.dashboard.revenue-trend") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    // Hide skeleton, show canvas
                    chartContainer.style.display = 'none';
                    canvas.style.display = 'block';

                    const ctx = canvas.getContext('2d');

                    if (revenueChart) revenueChart.destroy();

                    revenueChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Revenue ($)',
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
                            plugins: { legend: { position: 'bottom' } },
                            scales: {
                                y: {
                                    ticks: {
                                        callback: function (value) {
                                            return '$' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading revenue chart:', error);
                chartContainer.innerHTML = '<div class="empty-state" style="padding: 40px;"><i class="fas fa-chart-line"></i><p>Failed to load chart</p></div>';
            }
        }

        async function loadTodayAppointments() {
            const container = document.getElementById('todayAppointments');

            // Show loading spinner
            container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading appointments...</div>
            </div>
        `;

            try {
                const response = await fetch('{{ route("hospital.dashboard.today-appointments") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.appointments.length > 0) {
                    let html = `
                    <div class="appointments-table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                `;

                    data.appointments.forEach(apt => {
                        const statusClass = getStatusClass(apt.status);
                        const statusText = apt.status.charAt(0).toUpperCase() + apt.status.slice(1);
                        const avatarHtml = apt.patient_avatar_html || `<div class="patient-avatar-sm" style="display: flex; align-items: center; justify-content: center;"><span style="color: white; font-weight: 600;">${apt.patient_name.charAt(0)}</span></div>`;

                        html += `
                        <tr>
                            <td>${apt.time}</td>
                            <td>
                                <div class="patient-cell">
                                    <div class="patient-avatar-sm">${avatarHtml}</div>
                                    <span>${escapeHtml(apt.patient_name)}</span>
                                </div>
                            </td>
                            <td>${escapeHtml(apt.doctor_name)}</td>
                            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        </tr>
                    `;
                    });

                    html += `</tbody></table></div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No appointments scheduled for today.</p></div>`;
                }
            } catch (error) {
                console.error('Error loading today appointments:', error);
                container.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error loading appointments. Please refresh.</p></div>`;
            }
        }

        function getStatusClass(status) {
            switch (status) {
                case 'confirmed': return 'status-confirmed';
                case 'pending': return 'status-pending';
                case 'completed': return 'status-completed';
                case 'cancelled': return 'status-cancelled';
                default: return 'status-pending';
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }
    </script>
@endpush