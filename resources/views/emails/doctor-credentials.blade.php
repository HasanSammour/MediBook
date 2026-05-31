<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isReset ? 'Password Reset' : 'Welcome to MediBook' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .logo-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
        }

        .content {
            padding: 32px 28px;
        }

        .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-welcome {
            background: #d1fae5;
        }

        .icon-reset {
            background: #fef3c7;
        }

        .icon-welcome i {
            color: #10b981;
        }

        .icon-reset i {
            color: #f59e0b;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }

        .greeting {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .message {
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .credentials-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
            border-left: 4px solid #2563eb;
        }

        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .credential-row:last-child {
            border-bottom: none;
        }

        .credential-label {
            font-weight: 600;
            color: #4b5563;
        }

        .credential-value {
            font-family: monospace;
            font-size: 14px;
            background: white;
            padding: 4px 8px;
            border-radius: 6px;
            color: #2563eb;
        }

        .warning-box {
            background: #fef3c7;
            border-radius: 12px;
            padding: 12px 16px;
            margin: 20px 0;
            border-left: 3px solid #f59e0b;
        }

        .warning-box p {
            font-size: 13px;
            color: #92400e;
            margin: 0;
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
            background: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .content {
                padding: 24px 20px;
            }

            .title {
                font-size: 20px;
            }

            .credential-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-text">MediBook</div>
            <div class="logo-sub">Integrated Medical Appointment Management System</div>
        </div>

        <div class="content">
            @if($isReset)
                <div class="icon icon-reset">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="title">Password Reset</h1>
                <p class="greeting">Hello Dr. {{ $doctor->name }},</p>
                <p class="message">Your password has been reset. Please use the new credentials below to log in to your
                    account.</p>
            @else
                <div class="icon icon-welcome">
                    <i class="fas fa-user-md"></i>
                </div>
                <h1 class="title">Welcome to MediBook!</h1>
                <p class="greeting">Dear Dr. {{ $doctor->name }},</p>
                <p class="message">Your account has been created successfully. You can now log in to the MediBook platform
                    using the credentials below.</p>
            @endif

            <div class="credentials-card">
                <div class="credential-row">
                    <span class="credential-label">Email Address:</span>
                    <span class="credential-value">{{ $doctor->email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Temporary Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="warning-box">
                <p><strong>⚠️ Important:</strong> For security reasons, please change your password after your first
                    login.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn-new" style="color: white;">Login to Your Account</a>
            </div>

            <p style="margin-top: 24px; font-size: 13px; color: #6b7280; text-align: center;">
                If you have any questions, please contact your hospital administrator.
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} MediBook. All rights reserved.</p>
            <p>Need help? <a href="{{ url('/contact') }}">Contact Support</a></p>
        </div>
    </div>
</body>

</html>