@extends('layouts.auth')

@section('title', 'Login - MediBook')

@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            min-height: 100vh;
        }

        /* Login Container - Centered Card */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 60px;
        }

        .login-container {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }

        .login-card {
            background: var(--white);
            border-radius: 32px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section - Centered, clickable */
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-image {
            cursor: pointer;
            transition: opacity 0.3s ease;
            display: inline-block;
        }

        .logo-image:hover {
            opacity: 0.85;
        }

        .logo-image img {
            width: 70px;
            height: 70px;
            margin-bottom: 0.75rem;
        }

        .logo-text {
            cursor: pointer;
            transition: opacity 0.3s ease;
            display: block;
            text-decoration: none;
        }

        .logo-text:hover {
            opacity: 0.85;
            text-decoration: none;
        }

        .logo-text h2 {
            font-size: 2rem;
            margin-bottom: 0;
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title-section h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .title-section p {
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--dark-color);
        }

        .form-group label .required {
            color: var(--danger-color);
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
            font-size: 1rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--gray-color);
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--gray-color);
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider span {
            padding: 0 10px;
        }

        /* Footer Links */
        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 1.5rem;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 10px 12px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }

            .logo-image img {
                width: 55px;
                height: 55px;
            }

            .logo-text h2 {
                font-size: 1.6rem;
            }

            .title-section h1 {
                font-size: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-card">
                <!-- Logo Section - Centered, clickable -->
                <div class="logo-section">
                    <div class="logo-image" onclick="window.location.href='{{ route('home') }}'">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="MediBook Logo">
                    </div>
                    <div class="logo-text" onclick="window.location.href='{{ route('home') }}'">
                        <h2>MediBook</h2>
                    </div>
                </div>

                <!-- Title Section -->
                <div class="title-section">
                    <h1>Welcome Back</h1>
                    <p>Login to your account to continue</p>
                </div>

                <!-- Error Message -->
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                                required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">Login</button>
                </form>

                <!-- Register Link -->
                <div class="auth-footer">
                    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection