@extends('layouts.auth')

@section('title', 'Forgot Password - MediBook')

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
        margin-bottom: 1.5rem;
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

    .btn-send {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        border-radius: 12px;
    }

    .info-note {
        background: #f0f9ff;
        padding: 6px 12px;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.75rem;
        color: var(--primary-color);
        display: inline-block;
    }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
    }

    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .alert {
        padding: 10px 12px;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.8rem;
        text-align: left;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 3px solid #10b981;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #ef4444;
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
                <h1>Forgot Password?</h1>
                <p>Enter your email to reset your password</p>
            </div>

            <div class="info-note">
                <i class="fas fa-info-circle"></i> We'll send you a password reset link
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-send">Send Reset Link</button>
            </form>

            <div class="auth-footer">
                <a href="{{ route('login') }}">← Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection