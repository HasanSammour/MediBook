<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Status - MediBook</title>
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
            text-align: center;
        }
        .status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .status-icon.confirmed { background: #d1fae5; }
        .status-icon.cancelled { background: #fee2e2; }
        .status-icon.completed { background: #dbeafe; }
        .status-icon i { font-size: 30px; }
        .status-icon.confirmed i { color: #10b981; }
        .status-icon.cancelled i { color: #ef4444; }
        .status-icon.completed i { color: #2563eb; }
        .info-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
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
            @if($status === 'confirmed')
                <div class="status-icon confirmed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="title">Appointment Confirmed!</h1>
                <p style="text-align: center; color: #6b7280;">Your appointment has been confirmed by the doctor.</p>
            @elseif($status === 'cancelled')
                <div class="status-icon cancelled">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h1 class="title">Appointment Cancelled</h1>
                <p style="text-align: center; color: #6b7280;">Your appointment has been cancelled by the doctor.</p>
            @elseif($status === 'completed')
                <div class="status-icon completed">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <h1 class="title">Appointment Completed</h1>
                <p style="text-align: center; color: #6b7280;">Your appointment has been marked as completed.</p>
            @endif
            
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
                    <div class="info-label">👨‍⚕️ Doctor</div>
                    <div class="info-value">Dr. {{ $appointment->doctor->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">🏥 Hospital</div>
                    <div class="info-value">{{ $appointment->hospital->name ?? 'N/A' }}</div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/patient/dashboard') }}" class="btn">View My Appointments</a>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} MediBook. All rights reserved.</p>
        </div>
    </div>
</body>
</html>