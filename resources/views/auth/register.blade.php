@extends('layouts.auth')

@section('title', 'Register - MediBook')

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
            max-width: 550px;
            width: 100%;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--white);
            border-radius: 32px;
            padding: 2rem;
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

        .logo-section {
            text-align: center;
            margin-bottom: 1.5rem;
            cursor: pointer;
        }

        .logo-image img {
            width: 60px;
            height: 60px;
            margin-bottom: 0.5rem;
        }

        .logo-text h2 {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .title-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .title-section h1 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .title-section p {
            color: var(--gray-color);
            font-size: 0.8rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            font-size: 0.75rem;
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
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--gray-color);
            font-size: 0.7rem;
            margin-top: 0.5rem;
        }

        .checkbox-label input {
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .checkbox-label a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .btn-register {
            width: 100%;
            padding: 10px;
            font-size: 0.9rem;
            border-radius: 12px;
            margin-top: 0.5rem;
        }

        .info-note {
            background: #f0f9ff;
            padding: 8px 12px;
            border-radius: 12px;
            margin-top: 1rem;
            font-size: 0.7rem;
            color: var(--primary-color);
            text-align: center;
        }

        .info-note i {
            margin-right: 6px;
        }

        .alert {
            padding: 8px 12px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.75rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

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

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
                    <h1>Create Account</h1>
                    <p>Join MediBook to manage your health appointments</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number">
                        </div>
                    </div>

                    <!-- Gender and Date of Birth side by side -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Gender <span class="required">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-venus-mars"></i>
                                <select name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth <span class="required">*</span></label>
                            <div class="input-group">
                                <i class="fas fa-calendar"></i>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Create a password" required>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="Confirm your password"
                                required>
                        </div>
                    </div>

                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>

                    <button type="submit" class="btn btn-primary btn-register">Create Account</button>
                </form>

                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    Registration is only for Patients. Doctors and Hospital Admins are added by administrators.
                </div>

                <div class="auth-footer">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
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