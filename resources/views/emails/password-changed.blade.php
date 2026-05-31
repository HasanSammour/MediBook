<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed - MediBook</title>
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
            text-align: center;
        }
        .icon {
            width: 60px;
            height: 60px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .icon i {
            font-size: 30px;
            color: #2563eb;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1f2937;
        }
        .message {
            color: #6b7280;
            margin-bottom: 24px;
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
            <div class="icon">
                <i class="fas fa-lock"></i>
            </div>
            
            <h1 class="title">Password Changed</h1>
            <p class="message">Hello Dr. {{ $user->name }},</p>
            <p class="message">Your MediBook account password has been successfully changed.</p>
            <p class="message">If you did not make this change, please contact our support team immediately.</p>
            
            <a href="{{ url('/doctor/dashboard') }}" class="btn">Go to Dashboard</a>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} MediBook. All rights reserved.</p>
            <p>Need help? <a href="{{ url('/contact') }}" style="color: #2563eb;">Contact Support</a></p>
        </div>
    </div>
</body>
</html>