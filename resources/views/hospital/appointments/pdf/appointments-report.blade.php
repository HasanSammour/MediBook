<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointments Report - {{ $hospital->name }}</title>
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
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
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
        <p>Appointments Report</p>
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

    @if($filters['date_from'] || $filters['date_to'] || $filters['status'] != 'all')
    <div class="filters">
        <strong>Applied Filters:</strong>
        @if($filters['date_from']) From: {{ $filters['date_from'] }} @endif
        @if($filters['date_to']) To: {{ $filters['date_to'] }} @endif
        @if($filters['status'] != 'all') Status: {{ ucfirst($filters['status']) }} @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Specialty</th>
                <th>Status</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $index => $apt)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $apt->appointment_date->format('Y-m-d') }}</td>
                <td>{{ $apt->appointment_date->format('h:i A') }}</td>
                <td>{{ $apt->patient->name ?? 'N/A' }}</td>
                <td>{{ $apt->doctor->display_name ?? 'N/A' }}</td>
                <td>{{ $apt->doctor->specialization ?? 'General' }}</td>
                <td>
                    <span class="status-badge status-{{ $apt->status }}">
                        {{ ucfirst($apt->status) }}
                    </span>
                </td>
                <td>${{ number_format($apt->doctor->consultation_fee ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">No appointments found</td>
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