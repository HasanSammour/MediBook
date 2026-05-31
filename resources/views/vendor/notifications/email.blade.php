<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $level ?? 'MediBook' }}</title>
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
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .email-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 32px 24px;
            text-align: center;
        }
        
        .logo {
            margin-bottom: 8px;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: white;
            letter-spacing: 1px;
        }
        
        .logo-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
        }
        
        .email-body {
            padding: 32px 28px;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .badge-verification {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-reset {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-welcome {
            background: #d1fae5;
            color: #065f46;
        }
        
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .content-text {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .content-text p {
            margin-bottom: 16px;
        }
        
        .btn {
            display: inline-block;
            background: #2563eb;
            color: white !important;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            margin: 16px 0;
        }
        
        .btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #1f2937 !important;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .info-box {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
        
        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }
        
        .info-box a {
            color: #2563eb;
            text-decoration: none;
        }
        
        .action {
            text-align: center;
            margin: 24px 0;
        }
        
        .footer {
            background: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .footer-link {
            color: #2563eb;
            text-decoration: none;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        .subcopy {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        
        .subcopy p {
            margin-bottom: 8px;
        }
        
        .subcopy a {
            color: #2563eb;
            text-decoration: none;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .email-body {
                padding: 24px 20px;
            }
            .title {
                font-size: 20px;
            }
            .btn {
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="email-header">
                <div class="logo">
                    <span class="logo-text">MediBook</span>
                </div>
                <div class="logo-sub">Integrated Medical Appointment Management System</div>
            </div>
            
            <!-- Body -->
            <div class="email-body">
                <!-- Greeting -->
                @if (! empty($greeting))
                    <div class="greeting">{{ $greeting }}</div>
                @else
                    @if ($level === 'error')
                        <div class="greeting">Whoops!</div>
                    @else
                        <div class="greeting">Hello!</div>
                    @endif
                @endif
                
                <!-- Intro Lines -->
                @foreach ($introLines as $line)
                    <div class="content-text">
                        <p>{{ $line }}</p>
                    </div>
                @endforeach
                
                <!-- Action Button -->
                @isset($actionText)
                    <div class="action">
                        <a href="{{ $actionUrl }}" class="btn" style="color: white;">
                            {{ $actionText }}
                        </a>
                    </div>
                @endisset
                
                <!-- Outro Lines -->
                @foreach ($outroLines as $line)
                    <div class="content-text">
                        <p>{{ $line }}</p>
                    </div>
                @endforeach
                
                <!-- Info Box for Security Tips -->
                <div class="info-box">
                    <p>
                        <strong>🔒 Security Tip:</strong> If you did not request this email, no further action is required. 
                        You can safely ignore this message.
                    </p>
                </div>
                
                <!-- Regards -->
                <div class="content-text" style="margin-top: 24px;">
                    <p>
                        Thanks,<br>
                        <strong>The MediBook Team</strong>
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="footer-text">
                    © {{ date('Y') }} MediBook. All rights reserved.
                </div>
                <div class="footer-text">
                    <a href="{{ url('/') }}" class="footer-link">Visit Website</a> | 
                    <a href="{{ url('/contact') }}" class="footer-link">Contact Support</a>
                </div>
            </div>
            
            <!-- Subcopy (for plain text fallback) -->
            @isset($actionText)
                <div class="subcopy">
                    <p>
                        If you're having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
                        into your web browser:
                    </p>
                    <p>
                        <a href="{{ $actionUrl }}">{{ $actionUrl }}</a>
                    </p>
                </div>
            @endisset
        </div>
    </div>
</body>
</html>