<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome to MediBook</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #2563eb;
        }

        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0;
        }

        .header p {
            color: #6b7280;
            margin: 5px 0 0;
        }

        .content {
            padding: 20px 0;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .credentials-box {
            background: #f0f9ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }

        .credential-row {
            display: flex;
            margin-bottom: 10px;
        }

        .credential-label {
            width: 100px;
            font-weight: 600;
            color: #4b5563;
        }

        .credential-value {
            color: #1f2937;
            font-weight: 500;
        }

        .role-badge {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 13px;
        }

        .btn-new {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 16px;
        }

        .btn-new:hover {
            background: #4e83f7;
            color: white;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏥 MediBook</h1>
            <p>Integrated Medical Appointment Management System</p>
        </div>

        <div class="content">
            <div class="greeting">
                <strong>Hello {{ $user->name }},</strong>
            </div>

            <p>Welcome to MediBook! Your account has been created successfully.</p>

            <div class="credentials-box">
                <div class="credential-row">
                    <div class="credential-label">Email:</div>
                    <div class="credential-value">{{ $user->email }}</div>
                </div>
                <div class="credential-row">
                    <div class="credential-label">Password:</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
                <div class="credential-row">
                    <div class="credential-label">Role:</div>
                    <div class="credential-value"><span
                            class="role-badge">{{ ucfirst(str_replace('_', ' ', $role)) }}</span></div>
                </div>
            </div>

            <div class="warning">
                ⚠️ <strong>Important:</strong> For security reasons, please change your password after your first login.
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn-new" style="color: white;>Login to Your Account</a>
            </div>

            <p style="margin-top: 20px;">If you have any questions, please contact your system administrator.</p>

            <p>Best regards,<br><strong>MediBook Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} MediBook. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>

</html>