<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Changed - MediBook</title>
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
            max-width: 500px;
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
            text-align: center;
        }

        .icon {
            width: 70px;
            height: 70px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon i {
            font-size: 32px;
            color: #2563eb;
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1f2937;
        }

        p {
            color: #6b7280;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .info-box {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            text-align: left;
        }

        .info-box p {
            margin-bottom: 8px;
        }

        .info-box strong {
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
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .warning-box {
            background: #fef3c7;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #f59e0b;
        }

        .warning-box p {
            color: #92400e;
            margin-bottom: 0;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-text">MediBook</div>
            <div class="logo-sub">Integrated Medical Appointment Management System</div>
        </div>

        <div class="content">
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>

            <h2>Email Address Changed</h2>

            <p>Dear <strong>{{ $user->name }}</strong>,</p>

            <p>The email address associated with your MediBook account has been changed.</p>

            <div class="info-box">
                <p><strong>Previous Email:</strong> {{ $oldEmail }}</p>
                <p><strong>New Email:</strong> {{ $newEmail }}</p>
            </div>

            <div class="warning-box">
                <p><strong>⚠️ Security Notice</strong></p>
                <p>If you did not make this change, please contact our support team immediately to secure your account.
                </p>
            </div>

            <a href="{{ url('/contact') }}" class="btn">Contact Support</a>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} MediBook. All rights reserved.</p>
            <p><a href="{{ url('/') }}">Visit Website</a> | <a href="{{ url('/contact') }}">Contact Us</a></p>
        </div>
    </div>
</body>

</html>