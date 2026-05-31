@extends('layouts.auth')

@section('title', 'Reset Password - MediBook')

@push('styles')
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
        }

        .auth-container {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--white);
            border-radius: 32px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 0.6s ease;
            text-align: center;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-image img {
            width: 70px;
            height: 70px;
            margin-bottom: 0.75rem;
        }

        .logo-text h2 {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .title-section h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .title-section p {
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-reset {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border-radius: 12px;
            margin-top: 0.5rem;
        }

        .alert {
            padding: 10px 12px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
            text-align: left;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        .password-strength {
            margin-top: 0.25rem;
            font-size: 0.6rem;
        }

        .strength-weak {
            color: #ef4444;
        }

        .strength-medium {
            color: #f59e0b;
        }

        .strength-strong {
            color: #10b981;
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 1.5rem;
            }

            .logo-text h2 {
                font-size: 1.5rem;
            }

            .title-section h1 {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="logo-section">
                    <div class="logo-image" onclick="window.location.href='{{ route('home') }}'">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="MediBook Logo">
                    </div>
                    <div class="logo-text" onclick="window.location.href='{{ route('home') }}'">
                        <h2>MediBook</h2>
                    </div>
                </div>

                <div class="title-section">
                    <h1>Create New Password</h1>
                    <p>Please enter your new password below</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">

                    <div class="form-group">
                        <label>New Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter new password" required>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-reset">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const strengthDiv = document.getElementById('passwordStrength');

        passwordInput?.addEventListener('input', function () {
            const password = this.value;
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 'weak';
            let message = '';

            if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
                strength = 'strong';
                message = 'Strong password ✓';
            } else if (password.length >= 6 && (/[A-Z]/.test(password) || /[0-9]/.test(password))) {
                strength = 'medium';
                message = 'Medium password';
            } else {
                strength = 'weak';
                message = 'Weak password';
            }

            strengthDiv.innerHTML = `<span class="strength-${strength}">${message}</span>`;
        });
    </script>
@endsection