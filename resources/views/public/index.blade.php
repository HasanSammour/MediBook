@extends('layouts.guest')

@section('title', 'Your Health, Our Priority - MediBook')

@push('styles')
    <style>
        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: slideBackground 20s linear infinite;
        }

        @keyframes slideBackground {
            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(40px, 40px);
            }
        }

        .hero .container {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
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

        .hero-title .highlight {
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(37, 99, 235, 0.2);
            border-radius: 4px;
            z-index: -1;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            line-height: 1.6;
            color: var(--gray-color);
            margin-bottom: 2rem;
            max-width: 90%;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
        }

        .stat {
            text-align: left;
        }

        .stat h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat p {
            font-size: 0.875rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        /* Hero Cards */
        .hero-image {
            position: relative;
            height: 480px;
        }

        .hero-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
            position: absolute;
            width: 260px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hero-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.2);
        }

        .card-1 {
            top: 0;
            right: 0;
            z-index: 3;
        }

        .card-2 {
            bottom: 80px;
            left: 0;
            z-index: 2;
        }

        .card-3 {
            bottom: 0;
            right: 40px;
            z-index: 1;
        }

        .hero-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-card-header i {
            color: var(--primary-color);
            font-size: 0.75rem;
        }

        .hero-card-doctor {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .hero-card-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .hero-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-card-avatar svg {
            width: 45px;
            height: 45px;
        }

        .hero-card-doctor h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .hero-card-doctor p {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .hero-card-time {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-bottom: 12px;
        }

        .hero-card-time i {
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .hero-card-btn {
            width: 100%;
            padding: 8px 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hero-card-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .hero-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .hero-rating i {
            color: #fbbf24;
            font-size: 0.7rem;
        }

        .hero-rating span {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-left: 4px;
        }

        .hero-card-stats {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-top: 8px;
        }

        .hero-card-stats i {
            color: var(--primary-color);
            margin-right: 4px;
        }

        /* Search Section */
        .search-section {
            padding: 60px 0;
            background: var(--white);
        }

        .search-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: flex-end;
        }

        .search-group {
            position: relative;
        }

        .search-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
            letter-spacing: 0.3px;
        }

        .search-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .search-group input,
        .search-group select {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .search-group input:focus,
        .search-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-btn {
            display: flex;
            align-items: flex-end;
        }

        .search-btn button {
            padding: 12px 28px;
            font-size: 0.9rem;
            border-radius: 12px;
            white-space: nowrap;
        }

        /* Results Section */
        .results-header {
            margin: 2rem 0 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .results-count {
            font-size: 0.85rem;
            color: var(--gray-color);
        }

        .results-count span {
            color: var(--primary-color);
            font-weight: 700;
        }

        .result-card {
            display: flex;
            gap: 1.5rem;
            background: var(--white);
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .result-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            border-color: transparent;
        }

        .doctor-avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            background: #f3f4f6;
            flex-shrink: 0;
        }

        .doctor-avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-avatar-lg svg {
            width: 100%;
            height: 100%;
        }

        .doctor-info {
            flex: 1;
        }

        .doctor-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .specialty {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .hospital {
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rating {
            margin-bottom: 0.5rem;
        }

        .rating i {
            color: #fbbf24;
            font-size: 0.75rem;
        }

        .rating span {
            color: var(--gray-color);
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .fee {
            font-weight: 700;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .doctor-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Featured Hospitals Section */
        .hospitals-section {
            padding: 60px 0;
            background: var(--white);
        }

        .hospitals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .hospital-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hospital-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.12);
        }

        .hospital-image {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .hospital-card:hover .hospital-image img {
            transform: scale(1.05);
        }

        .hospital-logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.95);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .hospital-info {
            padding: 1.25rem;
        }

        .hospital-name h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .hospital-address {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gray-color);
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .hospital-address i {
            color: var(--primary-color);
            width: 14px;
            font-size: 0.7rem;
        }

        .hospital-stats {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-top: 1px solid #f0f0f0;
            margin-top: 0.5rem;
        }

        .hospital-stats .stat-item {
            text-align: center;
            flex: 1;
        }

        .hospital-stats .stat-value {
            font-size: 0.85rem;
            font-weight: 700;
        }

        .hospital-stats .stat-label {
            font-size: 0.65rem;
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: #f9fafb;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 1rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2rem 1.5rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .feature-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }

        .step {
            text-align: center;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .step-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .step-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .step h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: white;
        }

        .step p {
            font-size: 0.85rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 80px 0;
            background: var(--white);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .testimonial-card {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 20px;
            position: relative;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            background: var(--white);
        }

        .quote-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: var(--primary-light);
            opacity: 0.3;
        }

        .testimonial-text {
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .patient-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .patient-avatar-sm {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .patient-avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .patient-info h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .patient-info p {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        /* Newsletter Section */
        .newsletter {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: white;
        }

        .newsletter-content {
            text-align: center;
        }

        .newsletter-content h2 {
            color: white;
            margin-bottom: 0.5rem;
        }

        .newsletter-content p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
        }

        .newsletter-form {
            display: flex;
            justify-content: center;
            gap: 1rem;
            max-width: 450px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .newsletter-form input:focus {
            outline: none;
        }

        .newsletter-form button {
            white-space: nowrap;
        }

        /* Loading Spinner */
        .loading-spinner {
            text-align: center;
            padding: 40px;
            background: var(--white);
            border-radius: 20px;
        }

        .loading-spinner i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .loading-spinner p {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .hospitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 968px) {
            .hero .container {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2rem;
            }

            .hero-subtitle {
                max-width: 100%;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-buttons {
                justify-content: center;
            }

            .stat {
                text-align: center;
            }

            .hero-image {
                height: auto;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .hero-card {
                position: relative;
                top: auto;
                bottom: auto;
                left: auto;
                right: auto;
                width: 100%;
                max-width: 280px;
            }

            .search-form {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .search-btn button {
                width: 100%;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .steps {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .hospitals-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 100px 0 60px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-stats {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .result-card {
                flex-direction: column;
                text-align: center;
            }

            .doctor-avatar-lg {
                margin: 0 auto;
            }

            .doctor-actions {
                flex-direction: row;
                justify-content: center;
            }

            .features-section {
                padding: 60px 0;
            }

            .testimonials-section {
                padding: 60px 0;
            }

            .how-it-works {
                padding: 60px 0;
            }

            .newsletter-form {
                flex-direction: column;
                padding: 0 1rem;
            }

            .newsletter-form button {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .hero {
                padding: 80px 0 40px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero-buttons .btn {
                width: 100%;
                max-width: 250px;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }

            .result-card {
                padding: 1rem;
            }

            .doctor-info h3 {
                font-size: 1rem;
            }

            .doctor-actions {
                flex-direction: column;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .newsletter-content h2 {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Your Health, <span class="highlight">Our Priority</span></h1>
                <p class="hero-subtitle">Book appointments with top doctors, manage your health records, and get the care
                    you deserve - all in one place.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-large">Get Started</a>
                    <a href="{{ route('features') }}" class="btn btn-outline btn-large">Explore Features</a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <h3>{{ number_format($stats['hospitals']) }}+</h3>
                        <p>Partner Hospitals</p>
                    </div>
                    <div class="stat">
                        <h3>{{ number_format($stats['doctors']) }}+</h3>
                        <p>Qualified Doctors</p>
                    </div>
                    <div class="stat">
                        <h3>{{ number_format($stats['patients']) }}+</h3>
                        <p>Happy Patients</p>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-card card-1">
                    <div class="hero-card-header"><i class="fas fa-calendar-check"></i><span>Next Available</span></div>
                    <div class="hero-card-doctor">
                        <div class="hero-card-avatar"><i class="fas fa-user-md"></i></div>
                        <div>
                            <h4>Dr. Sarah Johnson</h4>
                            <p>Cardiologist</p>
                        </div>
                    </div>
                    <div class="hero-card-time"><i class="fas fa-clock"></i><span>Today at 10:30 AM</span></div>
                    <button class="hero-card-btn" onclick="bookNow()">Book Now</button>
                </div>
                <div class="hero-card card-2">
                    <div class="hero-card-header"><i class="fas fa-star"></i><span>Top Rated</span></div>
                    <div class="hero-card-doctor">
                        <div class="hero-card-avatar"><i class="fas fa-user-md"></i></div>
                        <div>
                            <h4>Dr. Michael Chen</h4>
                            <p>Neurologist</p>
                        </div>
                    </div>
                    <div class="hero-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span>5.0 (89 reviews)</span>
                    </div>
                </div>
                <div class="hero-card card-3">
                    <div class="hero-card-header"><i class="fas fa-hospital"></i><span>Near You</span></div>
                    <div class="hero-card-doctor">
                        <div class="hero-card-avatar"><i class="fas fa-building"></i></div>
                        <div>
                            <h4>City General Hospital</h4>
                            <p>2.5 km away</p>
                        </div>
                    </div>
                    <div class="hero-card-stats"><span><i class="fas fa-stethoscope"></i> {{ $stats['doctors'] }}+
                            Doctors</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="section-header">
                <h2>Find a Doctor</h2>
                <p>Search for doctors by name, specialty, or location</p>
            </div>
            <div class="search-container">
                <div class="search-form">
                    <div class="search-group">
                        <label>Doctor Name / Specialty</label>
                        <i class="fas fa-user-md"></i>
                        <input type="text" id="searchName" placeholder="Search by name or specialty...">
                    </div>
                    <div class="search-group">
                        <label>Location / Hospital</label>
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="searchLocation" placeholder="City or hospital...">
                    </div>
                    <div class="search-group">
                        <label>Specialty</label>
                        <i class="fas fa-stethoscope"></i>
                        <select id="searchSpecialty">
                            <option value="">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}">{{ $specialty }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-btn">
                        <button type="button" id="searchButton" class="btn btn-primary"><i class="fas fa-search"></i>
                            Search</button>
                    </div>
                </div>
            </div>
            <div id="resultsContainer">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading doctors...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Hospitals Section -->
    <section class="hospitals-section">
        <div class="container">
            <div class="section-header">
                <h2>Featured Hospitals</h2>
                <p>Partnered with the best healthcare institutions</p>
            </div>
            <div class="hospitals-grid">
                @forelse($featuredHospitals as $hospital)
                    @php
                        $logoUrl = $hospital->logo_url;
                        $isSvg = str_starts_with($logoUrl, 'data:image/svg+xml');
                        $doctorsCount = $hospital->doctors()->count();
                    @endphp
                    <div class="hospital-card">
                        <div class="hospital-image">
                            @if($isSvg)
                                <div class="hospital-logo-fallback">
                                    {!! $logoUrl !!}
                                </div>
                            @else
                                <img src="{{ $logoUrl }}" alt="{{ $hospital->name }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hospital-logo-fallback" style="display: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 100 100">
                                        <rect width="100" height="100" fill="#2563eb" rx="16" />
                                        <text x="50" y="65" text-anchor="middle" fill="white" font-size="45">🏥</text>
                                    </svg>
                                </div>
                            @endif
                            <div class="hospital-badge"><i class="fas fa-check-circle"></i> Verified</div>
                        </div>
                        <div class="hospital-info">
                            <div class="hospital-name">
                                <h3>{{ $hospital->name }}</h3>
                            </div>
                            <div class="hospital-address">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ Str::limit($hospital->address, 60) }}</span>
                            </div>
                            <div class="hospital-stats">
                                <div class="stat-item">
                                    <div class="stat-value"><i class="fas fa-user-md"></i> {{ $doctorsCount }}</div>
                                    <div class="stat-label">Doctors</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><i class="fas fa-calendar"></i>
                                        {{ $hospital->appointments_count ?? 0 }}</div>
                                    <div class="stat-label">Appointments</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="hospital-card" style="text-align: center; padding: 2rem;">
                        <p>No featured hospitals available.</p>
                    </div>
                @endforelse
            </div>
            <div class="view-all text-center mt-4">
                <a href="{{ route('hospitals') }}" class="btn btn-outline">View All Hospitals <i
                        class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose MediBook?</h2>
                <p>We make healthcare accessible, convenient, and reliable</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Easy Booking</h3>
                    <p>Book appointments with your preferred doctors in just a few clicks</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-search"></i></div>
                    <h3>Smart Search</h3>
                    <p>Find doctors by specialty, location, or hospital with advanced filters</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                    <h3>Secure Payments</h3>
                    <p>Track all your payments securely with our integrated payment system</p>
                </div>
            </div>
            <div class="view-all text-center mt-4">
                <a href="{{ route('features') }}" class="btn btn-outline">View All Features <i
                        class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 style="color: white;">How It Works</h2>
                <p style="color: rgba(255, 255, 255, 0.9);">Simple steps to get started with MediBook</p>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                    <h3>Create Account</h3>
                    <p>Register as a patient in seconds</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fas fa-search"></i></div>
                    <h3>Find a Doctor</h3>
                    <p>Search by specialty, location, or hospital</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3>Book Appointment</h3>
                    <p>Choose date and time that works for you</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="fas fa-stethoscope"></i></div>
                    <h3>Get Treatment</h3>
                    <p>Visit the doctor and receive care</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section - Static with Images -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2>What Our Patients Say</h2>
                <p>Join thousands of satisfied patients</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                    <p class="testimonial-text">"MediBook made finding a specialist so easy. I booked an appointment with a
                        cardiologist within minutes. Highly recommend!"</p>
                    <div class="patient-info">
                        <div class="patient-avatar-sm">
                            <img src="{{ asset('images/patients/patient1.jpg') }}" alt="John Doe"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <h4>John Doe</h4>
                            <p>Patient since 2024</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                    <p class="testimonial-text">"The platform is user-friendly and reliable. I can easily track my
                        appointments and medical history all in one place."</p>
                    <div class="patient-info">
                        <div class="patient-avatar-sm">
                            <img src="{{ asset('images/patients/patient2.jpg') }}" alt="Jane Smith"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <h4>Jane Smith</h4>
                            <p>Patient since 2024</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                    <p class="testimonial-text">"Excellent service! The doctors are professional and the booking process is
                        seamless. Best healthcare platform!"</p>
                    <div class="patient-info">
                        <div class="patient-avatar-sm">
                            <img src="{{ asset('images/patients/patient3.jpg') }}" alt="Robert Brown"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <h4>Robert Brown</h4>
                            <p>Patient since 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>Stay Updated with MediBook</h2>
                <p>Subscribe to our newsletter for health tips and updates</p>
                <form id="newsletterForm" class="newsletter-form">
                    @csrf
                    <input type="email" id="newsletterEmail" placeholder="Enter your email address" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial load - show default doctors
            performSearch();

            // Search on button click
            const searchButton = document.getElementById('searchButton');
            if (searchButton) {
                searchButton.addEventListener('click', performSearch);
            }

            // Search on Enter key press in text input fields only
            const searchName = document.getElementById('searchName');
            const searchLocation = document.getElementById('searchLocation');

            const handleKeyPress = function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            };

            if (searchName) searchName.addEventListener('keypress', handleKeyPress);
            if (searchLocation) searchLocation.addEventListener('keypress', handleKeyPress);

            // Dropdown - search immediately when option changes
            const searchSpecialty = document.getElementById('searchSpecialty');
            if (searchSpecialty) {
                searchSpecialty.addEventListener('change', function () {
                    performSearch();
                });
            }

            // Newsletter subscription
            const newsletterForm = document.getElementById('newsletterForm');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const email = document.getElementById('newsletterEmail').value;
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    if (!email) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Email Required',
                            text: 'Please enter your email address.',
                            confirmButtonColor: '#2563eb'
                        });
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const response = await fetch('{{ route("home.newsletter") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ email: email })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Subscribed!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            newsletterForm.reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Something went wrong',
                                confirmButtonColor: '#2563eb'
                            });
                        }
                    } catch (error) {
                        console.error('Newsletter error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error',
                            text: 'Please check your connection and try again.',
                            confirmButtonColor: '#2563eb'
                        });
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        });

        function bookNow() {
            @auth
                window.location.href = '{{ route("patient.search-doctors") }}';
            @else
                window.location.href = '{{ route("register") }}';
            @endauth
        }
        
        // View Profile function with auth check
        function viewDoctorProfile(id) {
            @auth
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch(`{{ url('patient/doctors') }}/${id}/profile`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const doctor = data.doctor;
                        Swal.fire({
                            title: doctor.name,
                            html: `
                                <div style="text-align: left;">
                                    <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                        <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden;">
                                            ${doctor.avatar_html ? doctor.avatar_html : `<div style="width: 100%; height: 100%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;">${doctor.name.charAt(0)}</div>`}
                                        </div>
                                    </div>
                                    <p><strong><i class="fas fa-stethoscope"></i> Specialty:</strong> ${doctor.specialty || 'General Physician'}</p>
                                    <p><strong><i class="fas fa-hospital"></i> Hospital:</strong> ${doctor.hospital}</p>
                                    <p><strong><i class="fas fa-briefcase"></i> Experience:</strong> ${doctor.experience}+ years</p>
                                    <p><strong><i class="fas fa-users"></i> Patients Treated:</strong> ${doctor.patients}+</p>
                                    <p><strong><i class="fas fa-star"></i> Rating:</strong> ${doctor.rating}/5</p>
                                    <p><strong><i class="fas fa-dollar-sign"></i> Consultation Fee:</strong> $${doctor.fee}</p>
                                    <hr>
                                    <p><strong><i class="fas fa-clock"></i> Availability:</strong></p>
                                    <p style="font-size: 0.85rem; color: #6b7280;">${doctor.availability}</p>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'Close',
                            width: '480px'
                        });
                    } else {
                        Swal.fire('Error', 'Could not load doctor details.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            @else
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to view doctor profile',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Login Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
            @endauth
        }

        // Book Appointment function
        function bookDoctorAppointment(id) {
            @auth
                // User is logged in - redirect to booking page
                window.location.href = `{{ url('patient/book') }}/${id}`;
            @else
                // User is not logged in - show login prompt
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to book an appointment',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Login Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
            @endauth
    }

        async function performSearch() {
            const name = document.getElementById('searchName')?.value || '';
            const location = document.getElementById('searchLocation')?.value || '';
            const specialty = document.getElementById('searchSpecialty')?.value || '';
            const container = document.getElementById('resultsContainer');

            if (!container) return;

            // Show loading
            container.innerHTML = `<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i><p>Searching for doctors...</p></div>`;

            try {
                const response = await fetch('{{ route("home.search.doctors") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, location, specialty })
                });

                const data = await response.json();

                if (!data.success || data.doctors.length === 0) {
                    container.innerHTML = `<div style="text-align: center; padding: 40px;">
                    <i class="fas fa-search" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                    <h3>No doctors found</h3>
                    <p>Try adjusting your search criteria</p>
                    <button onclick="resetSearch()" class="btn btn-outline" style="margin-top: 16px;">Reset Search</button>
                </div>`;
                    return;
                }

                let resultsHtml = `<div class="results-header"><span class="results-count">Found <span>${data.doctors.length}</span> doctors</span></div>`;

                data.doctors.forEach(doctor => {
                    const stars = generateStars(doctor.rating);
                    resultsHtml += `
                    <div class="result-card">
                        <div class="doctor-avatar-lg">
                            ${doctor.avatar_html ? doctor.avatar_html : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg, var(--primary-light), var(--primary-color));border-radius:50%;"><span style="font-size:2rem;font-weight:600;color:white;">${getInitials(doctor.name)}</span></div>`}
                        </div>
                        <div class="doctor-info">
                            <h3>${doctor.display_name}</h3>
                            <p class="specialty">${doctor.specialization || 'General Physician'}</p>
                            <p class="hospital"><i class="fas fa-hospital"></i> ${doctor.hospital || 'Independent Practice'}</p>
                            <div class="rating">${stars}<span>${doctor.rating} (${doctor.reviews} reviews)</span></div>
                            <p class="fee">Consultation Fee: $${doctor.consultation_fee}</p>
                        </div>
                        <div class="doctor-actions">
                            <button class="btn btn-outline btn-sm" onclick="viewDoctorProfile(${doctor.id})">View Profile</button>
                            <button class="btn btn-primary btn-sm" onclick="bookDoctorAppointment(${doctor.id})">Book Appointment</button>
                        </div>
                    </div>
                `;
                });

                container.innerHTML = resultsHtml;

            } catch (error) {
                console.error('Search error:', error);
                container.innerHTML = `<div style="text-align: center; padding: 40px; color: var(--danger-color);">
                <i class="fas fa-exclamation-circle" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                <h3>Something went wrong</h3>
                <p>Please try again later.</p>
            </div>`;
            }
        }

        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= Math.floor(rating); i++) stars += '<i class="fas fa-star"></i>';
            if (rating % 1 !== 0) stars += '<i class="fas fa-star-half-alt"></i>';
            for (let i = Math.ceil(rating); i < 5; i++) stars += '<i class="far fa-star"></i>';
            return stars;
        }

        function getInitials(name) {
            const cleanName = name.replace('Dr. ', '');
            const parts = cleanName.split(' ');
            if (parts.length >= 2) {
                return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
            }
            return cleanName.substring(0, 2).toUpperCase();
        }

        function resetSearch() {
            const searchName = document.getElementById('searchName');
            const searchLocation = document.getElementById('searchLocation');
            const searchSpecialty = document.getElementById('searchSpecialty');

            if (searchName) searchName.value = '';
            if (searchLocation) searchLocation.value = '';
            if (searchSpecialty) searchSpecialty.value = '';

            performSearch();
        }
    </script>
@endpush