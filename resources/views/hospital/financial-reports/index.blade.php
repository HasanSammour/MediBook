{{-- resources/views/hospital/financial-reports/index.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')
@section('page-subtitle', 'Analyze hospital revenue and financial performance')

@push('styles')
    <style>
        /* ============================================
           FILTER BAR - FULL WIDTH FIXED
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
        
        .stat-change {
            font-size: 0.65rem;
            margin-top: 4px;
        }
        
        .change-positive {
            color: #10b981;
        }
        
        .change-negative {
            color: #ef4444;
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
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* ============================================
           CHARTS ROW
           ============================================ */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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
        
        .chart-card h3 i {
            color: var(--primary-color);
        }
        
        .chart-container {
            height: 320px;
            position: relative;
        }
        
        canvas {
            max-height: 280px;
            width: 100% !important;
        }
        
        .chart-skeleton {
            height: 280px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        /* ============================================
           TOP DOCTORS TABLE
           ============================================ */
        .doctors-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
        
        .card-header h3 i {
            color: var(--primary-color);
        }
        
        .doctors-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
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
            flex-shrink: 0;
        }
        
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .doctor-avatar span {
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
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
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 10px;
            color: var(--gray-color);
            font-size: 0.85rem;
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
        
        .empty-state p {
            color: var(--gray-color);
            font-size: 0.85rem;
        }
        
        /* ============================================
           RESPONSIVE
           ============================================ */
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
            
            .chart-container {
                height: 280px;
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
    <!-- Filter Bar -->
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
                    <option value="1" {{ $currentMonth == 1 ? 'selected' : '' }}>January</option>
                    <option value="2" {{ $currentMonth == 2 ? 'selected' : '' }}>February</option>
                    <option value="3" {{ $currentMonth == 3 ? 'selected' : '' }}>March</option>
                    <option value="4" {{ $currentMonth == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $currentMonth == 5 ? 'selected' : '' }}>May</option>
                    <option value="6" {{ $currentMonth == 6 ? 'selected' : '' }}>June</option>
                    <option value="7" {{ $currentMonth == 7 ? 'selected' : '' }}>July</option>
                    <option value="8" {{ $currentMonth == 8 ? 'selected' : '' }}>August</option>
                    <option value="9" {{ $currentMonth == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $currentMonth == 10 ? 'selected' : '' }}>October</option>
                    <option value="11" {{ $currentMonth == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $currentMonth == 12 ? 'selected' : '' }}>December</option>
                </select>
            </div>
            <div class="filter-buttons">
                <button class="btn-generate" onclick="updateReports()">
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

    <!-- Charts Row -->
    <div class="charts-row">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Monthly Revenue Trend ({{ $currentYear }})</h3>
            <div class="chart-container">
                <div id="revenueSkeleton" class="chart-skeleton"></div>
                <canvas id="revenueChart" style="display: none;"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Payment Methods Distribution</h3>
            <div class="chart-container">
                <div id="paymentSkeleton" class="chart-skeleton"></div>
                <canvas id="paymentChart" style="display: none;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Doctors Table -->
    <div class="doctors-card">
        <div class="card-header">
            <h3><i class="fas fa-trophy"></i> Top Doctors by Revenue</h3>
        </div>
        <div id="doctorsTableContainer">
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading data...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let revenueChart = null;
        let paymentChart = null;
        let currentYear = {{ $currentYear }};
        let currentMonth = {{ $currentMonth }};

        async function updateReports() {
            currentYear = document.getElementById('yearSelect').value;
            currentMonth = document.getElementById('monthSelect').value;
            
            showLoadings();
            
            try {
                const response = await fetch(`{{ route('hospital.financial-reports.data') }}?year=${currentYear}&month=${currentMonth}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();
                
                if (data.success) {
                    updateStats(data.stats);
                    updateRevenueChart(data.revenue_trend);
                    updatePaymentChart(data.payment_methods);
                    updateTopDoctors(data.top_doctors);
                } else {
                    console.error('Error:', data);
                }
            } catch (error) {
                console.error('Error loading report data:', error);
            } finally {
                hideLoadings();
            }
        }
        
        function showLoadings() {
            // Stats skeletons
            const statsGrid = document.getElementById('statsGrid');
            statsGrid.innerHTML = `
                <div class="stat-skeleton"><div class="skeleton-info"><div class="skeleton-line-sm"></div><div class="skeleton-line-lg"></div></div><div class="skeleton-icon"></div></div>
                <div class="stat-skeleton"><div class="skeleton-info"><div class="skeleton-line-sm"></div><div class="skeleton-line-lg"></div></div><div class="skeleton-icon"></div></div>
                <div class="stat-skeleton"><div class="skeleton-info"><div class="skeleton-line-sm"></div><div class="skeleton-line-lg"></div></div><div class="skeleton-icon"></div></div>
                <div class="stat-skeleton"><div class="skeleton-info"><div class="skeleton-line-sm"></div><div class="skeleton-line-lg"></div></div><div class="skeleton-icon"></div></div>
            `;
            
            // Chart skeletons
            document.getElementById('revenueSkeleton').style.display = 'block';
            document.getElementById('revenueChart').style.display = 'none';
            document.getElementById('paymentSkeleton').style.display = 'block';
            document.getElementById('paymentChart').style.display = 'none';
            
            // Table loading
            document.getElementById('doctorsTableContainer').innerHTML = `
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading data...</div>
                </div>
            `;
        }
        
        function hideLoadings() {
            // Nothing to hide, content replaced directly
        }
        
        function updateStats(stats) {
            const statsGrid = document.getElementById('statsGrid');
            const revenueChangeClass = stats.revenue_change >= 0 ? 'change-positive' : 'change-negative';
            const revenueChangeSign = stats.revenue_change >= 0 ? '+' : '';
            
            statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Revenue</h4>
                        <div class="stat-value">$${stats.total_revenue.toLocaleString()}</div>
                        <div class="stat-change ${revenueChangeClass}">${revenueChangeSign}${stats.revenue_change}% vs last month</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Completed Appointments</h4>
                        <div class="stat-value">${stats.total_appointments}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Average Daily Revenue</h4>
                        <div class="stat-value">$${stats.avg_daily_revenue.toLocaleString()}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Payment Success Rate</h4>
                        <div class="stat-value">${stats.success_rate}%</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-percent"></i></div>
                </div>
            `;
        }
        
        function updateRevenueChart(trend) {
            // Hide skeleton, show canvas
            document.getElementById('revenueSkeleton').style.display = 'none';
            const canvas = document.getElementById('revenueChart');
            canvas.style.display = 'block';
            
            if (revenueChart) revenueChart.destroy();
            
            const ctx = canvas.getContext('2d');
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trend.labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: trend.values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb',
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
                        legend: { position: 'bottom' },
                        tooltip: { 
                            callbacks: { 
                                label: (ctx) => `$${ctx.raw.toLocaleString()}`
                            } 
                        }
                    },
                    scales: {
                        y: { 
                            ticks: { 
                                callback: (value) => '$' + value.toLocaleString()
                            } 
                        }
                    }
                }
            });
        }
        
        function updatePaymentChart(methods) {
            // Hide skeleton, show canvas
            document.getElementById('paymentSkeleton').style.display = 'none';
            const canvas = document.getElementById('paymentChart');
            canvas.style.display = 'block';
            
            if (paymentChart) paymentChart.destroy();
            
            const ctx = canvas.getContext('2d');
            paymentChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Card', 'Insurance'],
                    datasets: [{
                        data: [methods.cash, methods.card, methods.insurance],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { 
                            callbacks: { 
                                label: (ctx) => `$${ctx.raw.toLocaleString()}`
                            } 
                        }
                    }
                }
            });
        }
        
        function updateTopDoctors(doctors) {
            const container = document.getElementById('doctorsTableContainer');
            
            if (!doctors || doctors.length === 0) {
                container.innerHTML = '<div class="empty-state"><i class="fas fa-chart-simple"></i><p>No data available for the selected period</p></div>';
                return;
            }
            
            let html = `
                <div class="doctors-table-container">
                    <table class="doctors-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Rank</th>
                                <th>Doctor</th>
                                <th>Specialty</th>
                                <th style="width: 100px;">Appointments</th>
                                <th style="width: 120px;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            doctors.forEach(doctor => {
                let rankClass = '';
                if (doctor.rank === 1) rankClass = 'rank-1';
                else if (doctor.rank === 2) rankClass = 'rank-2';
                else if (doctor.rank === 3) rankClass = 'rank-3';
                
                // Handle avatar HTML properly
                let avatarHtml = '';
                if (doctor.avatar_html) {
                    avatarHtml = doctor.avatar_html;
                } else {
                    const initial = doctor.name ? doctor.name.charAt(0) : 'D';
                    avatarHtml = `<span>${escapeHtml(initial)}</span>`;
                }
                
                html += `
                    <tr>
                        <td><span class="rank-badge ${rankClass}">${doctor.rank}</span></td>
                        <td>
                            <div class="doctor-cell">
                                <div class="doctor-avatar">${avatarHtml}</div>
                                <span>${escapeHtml(doctor.name)}</span>
                            </div>
                        </td>
                        <td>${escapeHtml(doctor.specialty)}</td>
                        <td>${doctor.appointments}</td>
                        <td class="revenue-amount">$${doctor.revenue.toLocaleString()}</td>
                    </tr>
                `;
            });
            
            html += `</tbody>赶</div>`;
            container.innerHTML = html;
        }
        
        function exportReport() {
            const year = document.getElementById('yearSelect').value;
            const month = document.getElementById('monthSelect').value;
            window.location.href = `{{ route('hospital.financial-reports.export') }}?year=${year}&month=${month}`;
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
        
        document.addEventListener('DOMContentLoaded', function() {
            updateReports();
        });
    </script>
@endpush