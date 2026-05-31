{{-- resources/views/admin/reports/pdf/platform-report.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Platform Report - MediBook</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #1f2937;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
            margin: 5px 0 0;
        }

        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-box {
            flex: 1;
            background: #f9fafb;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box h4 {
            font-size: 8px;
            margin: 0 0 5px;
            color: #6b7280;
        }

        .stat-box .number {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #2563eb;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }

        .rank-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #e5e7eb;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            font-size: 8px;
            font-weight: 700;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .users-summary {
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>MediBook Platform Report</h1>
        <p>Period: {{ $month_name }} {{ $year }} | Generated on: {{ $export_date }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h4>Total Hospitals</h4>
            <div class="number">{{ number_format($stats['total_hospitals']) }}</div>
        </div>
        <div class="stat-box">
            <h4>Total Doctors</h4>
            <div class="number">{{ number_format($stats['total_doctors']) }}</div>
        </div>
        <div class="stat-box">
            <h4>Total Patients</h4>
            <div class="number">{{ number_format($stats['total_patients']) }}</div>
        </div>
        <div class="stat-box">
            <h4>Total Appointments</h4>
            <div class="number">{{ number_format($stats['total_appointments']) }}</div>
        </div>
    </div>

    <div class="section-title">Users Distribution</div>
    <div class="users-summary">
        <div class="summary-row">
            <span>Patients:</span>
            <strong>{{ number_format($users_distribution['patients']) }}</strong>
        </div>
        <div class="summary-row">
            <span>Doctors:</span>
            <strong>{{ number_format($users_distribution['doctors']) }}</strong>
        </div>
        <div class="summary-row">
            <span>Hospital Admins:</span>
            <strong>{{ number_format($users_distribution['hospital_admins']) }}</strong>
        </div>
        <div class="summary-row">
            <span>System Admins:</span>
            <strong>{{ number_format($users_distribution['system_admins']) }}</strong>
        </div>
    </div>

    <div class="section-title">Appointments by Hospital</div>
    <table>
        <thead>
            <tr>
                <th>Hospital Name</th>
                <th class="text-center">Pending</th>
                <th class="text-center">Confirmed</th>
                <th class="text-center">Completed</th>
                <th class="text-center">Cancelled</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments_by_hospital as $hospital)
                <tr>
                    <td>{{ $hospital->name }}</td>
                    <td class="text-center">{{ number_format($hospital->pending) }}</td>
                    <td class="text-center">{{ number_format($hospital->confirmed) }}</td>
                    <td class="text-center">{{ number_format($hospital->completed) }}</td>
                    <td class="text-center">{{ number_format($hospital->cancelled) }}</td>
                    <td class="text-center"><strong>{{ number_format($hospital->total) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Monthly Appointments Trend ({{ $year }})</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-center">Appointments</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthly_trend as $trend)
                <tr>
                    <td>{{ $trend['month'] }}</td>
                    <td class="text-center">{{ number_format($trend['count']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Top 10 Busiest Doctors</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Doctor Name</th>
                <th>Specialty</th>
                <th>Hospital</th>
                <th class="text-center">Appointments</th>
            </tr>
        </thead>
        <tbody>
            @forelse($busiest_doctors as $index => $doctor)
                <tr>
                    <td><span class="rank-badge">{{ $index + 1 }}</span></td>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->specialization ?? 'General' }}</td>
                    <td>{{ $doctor->hospital_name ?? 'Independent' }}</td>
                    <td class="text-center"><strong>{{ number_format($doctor->total) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by MediBook System</p>
        <p>&copy; {{ date('Y') }} MediBook - Integrated Medical Appointment Management System</p>
    </div>
</body>

</html>