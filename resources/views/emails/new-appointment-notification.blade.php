<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment - MediBook</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 30px 24px;
            text-align: center;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: white;
        }

        .content {
            padding: 32px 28px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1f2937;
        }

        .info-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
            border-left: 4px solid #10b981;
        }

        .info-row {
            margin-bottom: 12px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #1f2937;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 16px;
        }

        .footer {
            background: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-text">MediBook</div>
        </div>

        <div class="content">
            <h1 class="title">New Appointment Booked!</h1>
            <p style="color: #6b7280;">A patient has booked an appointment with you.</p>

            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">📅 Date</div>
                    <div class="info-value">{{ $appointment->appointment_date->format('l, F d, Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">⏰ Time</div>
                    <div class="info-value">{{ $appointment->appointment_date->format('h:i A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">👤 Patient Name</div>
                    <div class="info-value">{{ $patient->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">📞 Patient Phone</div>
                    <div class="info-value">{{ $patient->phone ?? 'Not provided' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">📧 Patient Email</div>
                    <div class="info-value">{{ $patient->email }}</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/doctor/dashboard') }}" class="btn">View in Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} MediBook. All rights reserved.</p>
        </div>
    </div>
</body>

</html>