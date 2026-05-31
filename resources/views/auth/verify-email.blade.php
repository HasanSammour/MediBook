@extends('layouts.auth')

@section('title', 'Verify Email - MediBook')

@push('styles')
    <style>
        .verify-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
        }

        .verify-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        .verify-card {
            background: white;
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .verify-icon {
            width: 80px;
            height: 80px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .verify-icon i {
            font-size: 2.5rem;
            color: #2563eb;
        }

        .verify-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .verify-text {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .email-display {
            background: #f3f4f6;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 1rem;
        }

        .btn-resend {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-resend:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .countdown {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 1rem;
        }

        .logout-link {
            display: inline-block;
            margin-top: 1rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .logout-link:hover {
            color: #2563eb;
        }
    </style>
@endpush

@section('content')
    <div class="verify-wrapper">
        <div class="verify-container">
            <div class="verify-card">
                <div class="verify-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>

                <h1 class="verify-title">Verify Your Email</h1>

                <p class="verify-text">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                    link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>

                <div class="email-display">
                    {{ auth()->user()->email }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 12px; margin-bottom: 1rem;">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                    @csrf
                    <button type="submit" class="btn-resend" id="resendBtn">Resend Verification Email</button>
                </form>

                <div class="countdown" id="countdown"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-link" style="background: none; border: none; cursor: pointer;">
                        ← Logout and use different account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let countdown = 60;
        let canResend = true;
        const resendBtn = document.getElementById('resendBtn');
        const countdownEl = document.getElementById('countdown');

        resendBtn.addEventListener('click', function (e) {
            if (!canResend) {
                e.preventDefault();
                return;
            }

            canResend = false;
            resendBtn.disabled = true;
            countdown = 60;

            const timer = setInterval(() => {
                countdown--;
                countdownEl.innerHTML = `You can request another link in ${countdown} seconds`;

                if (countdown <= 0) {
                    clearInterval(timer);
                    canResend = true;
                    resendBtn.disabled = false;
                    countdownEl.innerHTML = '';
                }
            }, 1000);
        });
    </script>
@endsection