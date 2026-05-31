{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Platform Reports')
@section('page-title', 'Platform Reports')
@section('page-subtitle', 'Analyze platform performance and statistics')

@push('styles')
    <style>
        /* ============================================
                   FILTER BAR - MATCH HOSPITAL ADMIN STYLE
                   ============================================ */
        .filter-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            justify-content: space-between;
        }

        .filter-group {
            flex: 1;
            min-width: 160px;
        }

        .filter-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            background: white;
            cursor: pointer;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .btn-generate {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-generate:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-export {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        /* ============================================
                   STATS CARDS
                   ============================================ */
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

        /* Loading Skeletons */
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
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* ============================================
                   FULL WIDTH CHART CARD
                   ============================================ */
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
            height: 350px;
            position: relative;
        }

        /* ============================================
                   TWO COLUMNS ROW
                   ============================================ */
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
            height: 280px;
            position: relative;
        }

        .chart-container-full canvas,
        .chart-container-half canvas {
            max-height: 100% !important;
            width: 100% !important;
        }

        /* Chart Skeletons - Updated heights */
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

        /* Empty State Styles */
        .empty-chart-state {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            border-radius: 12px;
            color: #6b7280;
        }

        .empty-chart-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 0.75rem;
        }

        .empty-chart-state p {
            font-size: 0.875rem;
            margin: 0;
        }

        .empty-chart-state small {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        /* ============================================
                   TOP DOCTORS TABLE
                   ============================================ */
        .doctors-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .doctors-table-container {
            overflow-x: auto;
        }

        .doctors-table {
            width: 100%;
            border-collapse: collapse;
        }

        .doctors-table th,
        .doctors-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.85rem;
        }

        .doctors-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
        }

        .doctor-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doctor-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .revenue-amount {
            font-weight: 700;
            color: #10b981;
        }

        .rank-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            background: #f3f4f6;
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .rank-1 {
            background: #fef3c7;
            color: #d97706;
        }

        .rank-2 {
            background: #e5e7eb;
            color: #6b7280;
        }

        .rank-3 {
            background: #fef3c7;
            color: #b45309;
        }

        /* Loading Spinner */
        .loading-container {
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

        .loading-text {
            margin-top: 10px;
            color: #6b7280;
            font-size: 0.875rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

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

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .filter-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-generate,
            .btn-export {
                justify-content: center;
                width: 100%;
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

            .chart-container-full {
                height: 300px;
            }

            .chart-container-half {
                height: 250px;
            }

            .doctors-table th,
            .doctors-table td {
                padding: 0.5rem;
                font-size: 0.75rem;
            }

            .doctor-avatar {
                width: 30px;
                height: 30px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filter Bar - Matching Hospital Admin Style -->
    <div class="filter-card">
        <div class="filter-row">
            <div class="filter-group">
                <label>Select Year</label>
                <select id="yearSelect">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Select Month</label>
                <select id="monthSelect">
                    <option value="">Full Year</option>
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-buttons">
                <button class="btn-generate" onclick="refreshAllData()">
                    <i class="fas fa-chart-line"></i> Generate Report
                </button>
                <button class="btn-export" onclick="exportReport()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid" id="statsGrid">
        <!-- Stats will be loaded here -->
    </div>

    <!-- Row 1: Appointments by Hospital (Full Width) -->
    <div class="chart-card-full">
        <h3><i class="fas fa-chart-bar"></i> Appointments by Hospital</h3>
        <div class="chart-container-full" id="appointmentsContainer">
            <div id="appointmentsSkeleton" class="chart-skeleton-full"></div>
            <canvas id="appointmentsChart" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Row 2: Users Distribution + Monthly Trend (2 Columns) -->
    <div class="charts-row">
        <div class="chart-card-half">
            <h3><i class="fas fa-chart-pie"></i> Users Distribution</h3>
            <div class="chart-container-half" id="usersContainer">
                <div id="usersSkeleton" class="chart-skeleton-half"></div>
                <canvas id="usersChart" style="display: none;"></canvas>
            </div>
        </div>
        <div class="chart-card-half">
            <h3><i class="fas fa-chart-line"></i> Monthly Appointments Trend</h3>
            <div class="chart-container-half" id="trendContainer">
                <div id="trendSkeleton" class="chart-skeleton-half"></div>
                <canvas id="trendChart" style="display: none;"></canvas>
            </div>
        </div>
    </div>

    <!-- Row 3: Busiest Doctors (Full Width) -->
    <div class="chart-card-full">
        <h3><i class="fas fa-trophy"></i> Busiest Doctors (Top 10)</h3>
        <div class="chart-container-full" id="doctorsContainer">
            <div id="doctorsSkeleton" class="chart-skeleton-full"></div>
            <canvas id="doctorsChart" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Top Doctors Table -->
    <div class="doctors-card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> Top Doctors by Appointments</h3>
        </div>
        <div id="doctorsTableContainer">
            <!-- Table will be loaded here -->
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let appointmentsChart = null;
        let usersChart = null;
        let trendChart = null;
        let doctorsChart = null;
        let currentYear = {{ $currentYear }};
        let currentMonth = '';
        let isLoading = false;

        document.addEventListener('DOMContentLoaded', function () {
            refreshAllData();

            document.getElementById('yearSelect').addEventListener('change', function () {
                currentYear = this.value;
                refreshAllData();
            });

            document.getElementById('monthSelect').addEventListener('change', function () {
                currentMonth = this.value;
                refreshAllData();
            });
        });

        function refreshAllData() {
            if (isLoading) return;
            isLoading = true;

            showLoadings();

            Promise.all([
                loadStats(),
                loadAppointmentsByHospital(),
                loadUsersDistribution(),
                loadMonthlyTrend(),
                loadBusiestDoctors()
            ]).finally(() => {
                isLoading = false;
            });
        }

        function showLoadings() {
            // Stats skeletons
            const statsGrid = document.getElementById('statsGrid');
            statsGrid.innerHTML = `
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
                `;

            // Show all skeletons, hide charts
            const appointmentsSkeleton = document.getElementById('appointmentsSkeleton');
            const appointmentsChart = document.getElementById('appointmentsChart');
            if (appointmentsSkeleton) appointmentsSkeleton.style.display = 'block';
            if (appointmentsChart) appointmentsChart.style.display = 'none';

            const usersSkeleton = document.getElementById('usersSkeleton');
            const usersChart = document.getElementById('usersChart');
            if (usersSkeleton) usersSkeleton.style.display = 'block';
            if (usersChart) usersChart.style.display = 'none';

            const trendSkeleton = document.getElementById('trendSkeleton');
            const trendChart = document.getElementById('trendChart');
            if (trendSkeleton) trendSkeleton.style.display = 'block';
            if (trendChart) trendChart.style.display = 'none';

            const doctorsSkeleton = document.getElementById('doctorsSkeleton');
            const doctorsChart = document.getElementById('doctorsChart');
            if (doctorsSkeleton) doctorsSkeleton.style.display = 'block';
            if (doctorsChart) doctorsChart.style.display = 'none';

            // Table loading
            const tableContainer = document.getElementById('doctorsTableContainer');
            if (tableContainer) {
                tableContainer.innerHTML = `
                        <div class="loading-container">
                            <div class="loading-spinner"></div>
                            <div class="loading-text">Loading data...</div>
                        </div>
                    `;
            }
        }

        function showEmptyState(containerId, skeletonId, chartId, message) {
            const skeleton = document.getElementById(skeletonId);
            const chart = document.getElementById(chartId);
            const container = document.getElementById(containerId);

            if (skeleton) {
                skeleton.innerHTML = `
                        <div class="empty-chart-state">
                            <i class="fas fa-chart-line"></i>
                            <p>${message}</p>
                            <small>Try changing the filters</small>
                        </div>
                    `;
                skeleton.style.display = 'block';
            }
            if (chart) chart.style.display = 'none';
        }

        async function loadStats() {
            const statsGrid = document.getElementById('statsGrid');

            try {
                const url = `{{ route("admin.reports.stats") }}?year=${currentYear}&month=${currentMonth}`;
                const response = await fetch(url, {
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
                } else {
                    statsGrid.innerHTML = `
                            <div class="stat-card">
                                <div class="stat-info">
                                    <h4>Error</h4>
                                    <div class="stat-value">--</div>
                                </div>
                                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            </div>
                        `.repeat(4);
                }
            } catch (error) {
                console.error('Error loading stats:', error);
                statsGrid.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4>Error</h4>
                                <div class="stat-value">--</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                    `.repeat(4);
            }
        }

        async function loadAppointmentsByHospital() {
            const skeleton = document.getElementById('appointmentsSkeleton');
            const canvas = document.getElementById('appointmentsChart');

            try {
                const url = `{{ route("admin.reports.appointments-by-hospital") }}?year=${currentYear}&month=${currentMonth}`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels && data.labels.length > 0) {
                    skeleton.style.display = 'none';
                    canvas.style.display = 'block';

                    if (appointmentsChart) appointmentsChart.destroy();

                    const ctx = canvas.getContext('2d');
                    appointmentsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Pending',
                                    data: data.pending,
                                    backgroundColor: '#f59e0b',
                                    borderRadius: 4,
                                    stack: 'stack0'
                                },
                                {
                                    label: 'Confirmed',
                                    data: data.confirmed,
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 4,
                                    stack: 'stack0'
                                },
                                {
                                    label: 'Completed',
                                    data: data.completed,
                                    backgroundColor: '#10b981',
                                    borderRadius: 4,
                                    stack: 'stack0'
                                },
                                {
                                    label: 'Cancelled',
                                    data: data.cancelled,
                                    backgroundColor: '#ef4444',
                                    borderRadius: 4,
                                    stack: 'stack0'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, padding: 8 }
                                },
                                tooltip: {
                                    callbacks: { label: (ctx) => `${ctx.dataset.label}: ${ctx.raw}` }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: { autoSkip: true, maxRotation: 45, minRotation: 45 }
                                },
                                y: {
                                    title: { display: true, text: 'Number of Appointments' }
                                }
                            },
                            layout: {
                                padding: {
                                    top: 10,
                                    bottom: 10,
                                    left: 10,
                                    right: 10
                                }
                            }
                        }
                    });
                } else {
                    showEmptyState('appointmentsContainer', 'appointmentsSkeleton', 'appointmentsChart', 'No appointment data available');
                }
            } catch (error) {
                console.error('Error loading appointments chart:', error);
                showEmptyState('appointmentsContainer', 'appointmentsSkeleton', 'appointmentsChart', 'Failed to load chart data');
            }
        }

        async function loadUsersDistribution() {
            const skeleton = document.getElementById('usersSkeleton');
            const canvas = document.getElementById('usersChart');

            try {
                const response = await fetch('{{ route("admin.reports.users-distribution") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels && data.labels.length > 0 && data.values.some(v => v > 0)) {
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
                                backgroundColor: data.colors || ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                                borderWidth: 0,
                                cutout: '60%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, padding: 8 }
                                }
                            }
                        }
                    });
                } else {
                    showEmptyState('usersContainer', 'usersSkeleton', 'usersChart', 'No user data available');
                }
            } catch (error) {
                console.error('Error loading users chart:', error);
                showEmptyState('usersContainer', 'usersSkeleton', 'usersChart', 'Failed to load user data');
            }
        }

        async function loadMonthlyTrend() {
            const skeleton = document.getElementById('trendSkeleton');
            const canvas = document.getElementById('trendChart');

            try {
                const url = `{{ route("admin.reports.monthly-trend") }}?year=${currentYear}`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels && data.labels.length > 0 && data.values.some(v => v > 0)) {
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
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, padding: 8 }
                                }
                            }
                        }
                    });
                } else {
                    showEmptyState('trendContainer', 'trendSkeleton', 'trendChart', 'No trend data available for ' + currentYear);
                }
            } catch (error) {
                console.error('Error loading trend chart:', error);
                showEmptyState('trendContainer', 'trendSkeleton', 'trendChart', 'Failed to load trend data');
            }
        }

        async function loadBusiestDoctors() {
            const skeleton = document.getElementById('doctorsSkeleton');
            const canvas = document.getElementById('doctorsChart');
            const tableContainer = document.getElementById('doctorsTableContainer');

            try {
                const url = `{{ route("admin.reports.busiest-doctors") }}?year=${currentYear}&month=${currentMonth}`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.labels && data.labels.length > 0) {
                    skeleton.style.display = 'none';
                    canvas.style.display = 'block';

                    if (doctorsChart) doctorsChart.destroy();

                    const ctx = canvas.getContext('2d');
                    doctorsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Appointments',
                                data: data.values,
                                backgroundColor: '#f59e0b',
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, padding: 8 }
                                }
                            },
                            indexAxis: 'y',
                            layout: {
                                padding: {
                                    top: 10,
                                    bottom: 10,
                                    left: 10,
                                    right: 10
                                }
                            }
                        }
                    });

                    // Build Table
                    let tableHtml = `
                            <div class="doctors-table-container">
                                <table class="doctors-table">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Doctor Name</th>
                                            <th>Specialty</th>
                                            <th>Hospital</th>
                                            <th>Completed</th>
                                            <th>Total Appointments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                    if (data.doctors && data.doctors.length > 0) {
                        data.doctors.forEach((doctor, index) => {
                            let rankClass = '';
                            if (index === 0) rankClass = 'rank-1';
                            else if (index === 1) rankClass = 'rank-2';
                            else if (index === 2) rankClass = 'rank-3';

                            tableHtml += `
                                    <tr>
                                        <td><span class="rank-badge ${rankClass}">${index + 1}</span></td>
                                        <td><strong>${escapeHtml(doctor.name)}</strong></td>
                                        <td>${escapeHtml(doctor.specialty)}</td>
                                        <td>${escapeHtml(doctor.hospital)}</td>
                                        <td>${doctor.completed || 0}</td>
                                        <td><strong>${doctor.appointments}</strong></td>
                                    </tr>
                                `;
                        });
                    } else {
                        tableHtml += `
                                <tr>
                                    <td colspan="6" style="text-align: center;">No doctor data available</td>
                                </tr>
                            `;
                    }

                    tableHtml += `</tbody></table></div>`;
                    tableContainer.innerHTML = tableHtml;
                } else {
                    showEmptyState('doctorsContainer', 'doctorsSkeleton', 'doctorsChart', 'No doctor data available');
                    tableContainer.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <p>No doctor data available for the selected filters</p>
                                <small>Try changing the year or month</small>
                            </div>
                        `;
                }
            } catch (error) {
                console.error('Error loading busiest doctors:', error);
                showEmptyState('doctorsContainer', 'doctorsSkeleton', 'doctorsChart', 'Failed to load doctor data');
                tableContainer.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Failed to load doctor data</p>
                            <small>Please try again later</small>
                        </div>
                    `;
            }
        }

        function exportReport() {
            window.location.href = `{{ route("admin.reports.export") }}?year=${currentYear}&month=${currentMonth}`;
            window.open(url, '_blank');
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