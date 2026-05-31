<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report - {{ $hospital->name }}</title>
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
        .hospital-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .hospital-info table {
            width: 100%;
        }
        .hospital-info td {
            padding: 2px 0;
            font-size: 9px;
        }
        .period {
            text-align: center;
            margin-bottom: 20px;
            padding: 8px;
            background: #eff6ff;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
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
        .stat-box .amount {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
        }
        .stat-box .number {
            font-size: 14px;
            font-weight: 700;
            color: #10b981;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #2563eb;
            color: #1f2937;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .revenue-amount {
            font-weight: 700;
            color: #10b981;
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
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $hospital->name }}</h1>
        <p>Financial Report</p>
        <p>Generated on: {{ $export_date }}</p>
    </div>

    <div class="hospital-info">
        <table>
            <tr>
                <td width="50%"><strong>Address:</strong> {{ $hospital->address }}</td>
                <td width="50%"><strong>Phone:</strong> {{ $hospital->phone }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong> {{ $hospital->email }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="period">
        Report Period: {{ $month_name }}
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h4>Total Revenue</h4>
            <div class="amount">${{ number_format($total_revenue, 2) }}</div>
        </div>
        <div class="stat-box">
            <h4>Completed Appointments</h4>
            <div class="number">{{ $total_appointments }}</div>
        </div>
        <div class="stat-box">
            <h4>Average Daily Revenue</h4>
            <div class="amount">${{ number_format($avg_daily_revenue, 2) }}</div>
        </div>
        <div class="stat-box">
            <h4>Payment Success Rate</h4>
            <div class="number">{{ $success_rate }}%</div>
        </div>
    </div>

    <div class="section-title">Payment Methods Summary</div>
    <div class="summary-row">
        <span>Cash:</span>
        <span class="revenue-amount">${{ number_format($cash_total, 2) }}</span>
    </div>
    <div class="summary-row">
        <span>Card:</span>
        <span class="revenue-amount">${{ number_format($card_total, 2) }}</span>
    </div>
    <div class="summary-row">
        <span>Insurance:</span>
        <span class="revenue-amount">${{ number_format($insurance_total, 2) }}</span>
    </div>

    <div class="section-title">Top Doctors by Revenue</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Doctor</th>
                <th>Specialty</th>
                <th>Appointments</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($top_doctors as $doctor)
            <tr>
                <td><span class="rank-badge">{{ $doctor['rank'] }}</span></td>
                <td>{{ $doctor['name'] }}</td>
                <td>{{ $doctor['specialty'] }}</td>
                <td>{{ $doctor['appointments'] }}</td>
                <td class="revenue-amount">${{ number_format($doctor['revenue'], 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Recent Payments (Last 50)</div>
    <table>
        <thead>
            <tr>
                <th>Receipt ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $payment->appointment->patient->name ?? 'N/A' }}</td>
                <td>{{ $payment->appointment->doctor->display_name ?? 'N/A' }}</td>
                <td class="revenue-amount">${{ number_format($payment->amount, 2) }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No payments recorded</td>
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