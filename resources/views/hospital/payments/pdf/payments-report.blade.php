<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payments Report - {{ $hospital->name }}</title>
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

        .summary {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .summary-box {
            flex: 1;
            background: #f3f4f6;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-box h4 {
            font-size: 8px;
            margin: 0 0 5px;
            color: #6b7280;
        }

        .summary-box .amount {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
        }

        .filters {
            margin-bottom: 15px;
            padding: 8px;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 8px;
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

        .amount {
            font-weight: 700;
            color: #10b981;
        }

        .method-cash {
            background: #d1fae5;
            color: #065f46;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .method-card {
            background: #dbeafe;
            color: #1e40af;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .method-insurance {
            background: #fef3c7;
            color: #92400e;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $hospital->name }}</h1>
        <p>Payments Report</p>
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

    <div class="summary">
        <div class="summary-box">
            <h4>Total Revenue</h4>
            <div class="amount">${{ number_format($summary['total_revenue'], 2) }}</div>
        </div>
        <div class="summary-box">
            <h4>Total Transactions</h4>
            <div class="amount">{{ $summary['total_count'] }}</div>
        </div>
        <div class="summary-box">
            <h4>Cash</h4>
            <div class="amount">${{ number_format($summary['cash_total'], 2) }}</div>
        </div>
        <div class="summary-box">
            <h4>Card</h4>
            <div class="amount">${{ number_format($summary['card_total'], 2) }}</div>
        </div>
        <div class="summary-box">
            <h4>Insurance</h4>
            <div class="amount">${{ number_format($summary['insurance_total'], 2) }}</div>
        </div>
    </div>

    @if($filters['date_from'] || $filters['date_to'] || $filters['method'] != 'all')
        <div class="filters">
            <strong>Applied Filters:</strong>
            @if($filters['date_from']) From: {{ $filters['date_from'] }} @endif
            @if($filters['date_to']) To: {{ $filters['date_to'] }} @endif
            @if($filters['method'] != 'all') Method: {{ ucfirst($filters['method']) }} @endif
        </div>
    @endif

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
            @forelse($payments as $index => $payment)
                @php
                    $receiptNo = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
                @endphp
                <tr>
                    <td>{{ $receiptNo }}</td>
                    <td>{{ $payment->appointment->patient->name ?? 'N/A' }}</td>
                    <td>{{ $payment->appointment->doctor->display_name ?? 'N/A' }}</td>
                    <td class="amount">${{ number_format($payment->amount, 2) }}</td>
                    <td><span class="method-{{ $payment->payment_method }}">{{ ucfirst($payment->payment_method) }}</span>
                    </td>
                    <td>{{ $payment->payment_date->format('Y-m-d h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No payments found</td>
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