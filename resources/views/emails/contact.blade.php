<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message - MediBook</title>
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
            padding: 30px 24px;
            text-align: center;
        }

        .logo {
            margin-bottom: 16px;
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
            padding: 32px 24px;
        }

        .badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .info-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
            border-left: 4px solid #2563eb;
        }

        .info-row {
            margin-bottom: 16px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #1f2937;
            word-break: break-word;
        }

        .message-box {
            background: #fef3c7;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
        }

        .message-label {
            font-size: 12px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #78350f;
            white-space: pre-wrap;
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

        .btn-reply {
            display: inline-block;
            color: white !important;
            background: #2563eb;
            padding: 10px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            margin-top: 16px;
        }

        .btn-reply:hover {
            background: #1547d1;
            color: white;
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
                <span class="badge">📬 New Contact Form Submission</span>

                <h1 class="title">You have a new message</h1>
                <p style="color: #6b7280; margin-bottom: 24px;">Someone has contacted you through the MediBook contact
                    form.</p>

                <!-- Sender Information -->
                <div class="info-card">
                    <div class="info-row">
                        <div class="info-label">📝 Full Name</div>
                        <div class="info-value">{{ $data['name'] }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">📧 Email Address</div>
                        <div class="info-value">{{ $data['email'] }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">📌 Subject</div>
                        <div class="info-value">{{ $data['subject'] }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">📞 Phone Number</div>
                        <div class="info-value">{{ $data['phone'] ?? 'Not provided' }}</div>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="message-box">
                    <div class="message-label">
                        <span>💬 Message Content</span>
                    </div>
                    <div class="message-text">
                        {{ $data['message'] }}
                    </div>
                </div>

                <!-- Action Button -->
                <div style="text-align: center; color: white;">
                    <a href="mailto:{{ $data['email'] }}" class="btn-reply">
                        📧 Reply to {{ $data['name'] }}
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-text">
                    © {{ date('Y') }} MediBook. All rights reserved.
                </div>
                <div class="footer-text">
                    <a href="{{ url('/') }}" class="footer-link">Visit Website</a> |
                    <a href="{{ url('/contact') }}" class="footer-link">Contact Us</a>
                </div>
                <div class="footer-text" style="font-size: 11px;">
                    This message was sent from the MediBook contact form.
                </div>
            </div>
        </div>
    </div>
</body>

</html>