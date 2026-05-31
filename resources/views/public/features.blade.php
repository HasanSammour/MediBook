@extends('layouts.guest')

@section('title', 'Features - MediBook')

@push('styles')
<style>
    /* Page Header with Animation */
    .page-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
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
        from { transform: translate(0, 0); }
        to { transform: translate(40px, 40px); }
    }

    .page-header h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        animation: fadeInDown 0.8s ease;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.125rem;
        color: var(--gray-color);
        max-width: 600px;
        margin: 0 auto;
        animation: fadeInUp 0.8s ease;
        position: relative;
        z-index: 1;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Features Grid Section */
    .features-grid-section {
        padding: 80px 0;
        background: var(--white);
    }

    .section-badge {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        animation: slideInLeft 0.6s ease;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 2rem;
    }

    .feature-card {
        background: var(--white);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
        opacity: 0;
        transform: translateY(30px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .feature-card.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .feature-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        border-color: rgba(37, 99, 235, 0.2);
    }

    .feature-icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.4s ease;
    }

    .feature-card:hover .feature-icon-wrapper {
        border-radius: 50%;
        transform: rotate(360deg) scale(1.1);
    }

    .feature-icon-wrapper i {
        font-size: 2rem;
        color: white;
        transition: all 0.4s ease;
    }

    .feature-card h3 {
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }

    .feature-card:hover h3 {
        color: var(--primary-color);
    }

    .feature-card p {
        color: var(--gray-color);
        line-height: 1.6;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .feature-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
    }

    .feature-card:hover .feature-link {
        opacity: 1;
        transform: translateY(0);
    }

    .feature-link:hover {
        gap: 12px;
        color: var(--primary-dark);
    }

    /* Role Cards Section */
    .role-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }

    .role-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--primary-light), transparent);
    }

    .role-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .role-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.4s ease;
        opacity: 0;
        transform: scale(0.95);
        cursor: default;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .role-card.animate-in {
        opacity: 1;
        transform: scale(1);
    }

    .role-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(37, 99, 235, 0.2);
    }

    .role-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.4s ease;
    }

    .role-card:hover .role-icon {
        transform: rotateY(180deg);
    }

    .role-icon i {
        font-size: 2.2rem;
        color: white;
        transition: all 0.4s ease;
    }

    .role-card:hover .role-icon i {
        transform: rotateY(-180deg);
    }

    .role-card h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .role-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }

    .role-card ul li {
        padding: 0.6rem 0;
        font-size: 0.85rem;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .role-card:hover ul li {
        transform: translateX(5px);
    }

    .role-card ul li i {
        color: var(--secondary-color);
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .role-card:hover ul li i {
        transform: scale(1.2);
    }

    /* Stats Section */
    .stats-section {
        padding: 80px 0;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stats-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: slideStats 30s linear infinite;
    }

    @keyframes slideStats {
        from { transform: translate(0, 0); }
        to { transform: translate(50px, 50px); }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .stat-item {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }

    .stat-item.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    /* CTA Section */
    .cta-section {
        padding: 80px 0;
        background: var(--white);
        text-align: center;
    }

    .cta-card {
        background: linear-gradient(135deg, #f0f9ff, #e6f3ff);
        border-radius: 40px;
        padding: 3.5rem;
        max-width: 800px;
        margin: 0 auto;
        transition: all 0.4s ease;
        opacity: 0;
        transform: translateY(30px);
    }

    .cta-card.animate-in {
        opacity: 1;
        transform: translateY(0);
    }

    .cta-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 30px 50px rgba(0, 0, 0, 0.15);
    }

    .cta-card h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .cta-card p {
        color: var(--gray-color);
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-buttons .btn {
        transition: all 0.3s ease;
    }

    .cta-buttons .btn:hover {
        transform: translateY(-3px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .role-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 968px) {
        .page-header h1 {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 100px 0 40px;
        }
        .page-header h1 {
            font-size: 2rem;
        }
        .features-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .role-cards {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .stat-number {
            font-size: 2.5rem;
        }
        .cta-card {
            padding: 2rem 1.5rem;
        }
        .cta-card h2 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        .page-header p {
            font-size: 0.9rem;
        }
        .feature-card {
            padding: 1.5rem;
        }
        .role-card {
            padding: 1.5rem;
        }
        .cta-buttons {
            flex-direction: column;
        }
        .cta-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Powerful Features for <span class="highlight">Better Healthcare</span></h1>
        <p>Everything you need to manage medical appointments efficiently</p>
    </div>
</section>

<!-- Features Grid Section -->
<section class="features-grid-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Platform Capabilities</span>
            <h2>Everything You Need in One Platform</h2>
            <p>Comprehensive features designed for all healthcare stakeholders</p>
        </div>
        <div class="features-grid" id="featuresGrid">
            <div class="feature-card" data-delay="0">
                <div class="feature-icon-wrapper"><i class="fas fa-calendar-check"></i></div>
                <h3>Easy Appointment Booking</h3>
                <p>Book appointments with your preferred doctors in just a few clicks. Choose date, time, and get instant confirmation.</p>
                <span class="feature-link">For Patients <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="feature-card" data-delay="100">
                <div class="feature-icon-wrapper"><i class="fas fa-search"></i></div>
                <h3>Smart Doctor Search</h3>
                <p>Find doctors by specialty, location, hospital, or patient reviews. Advanced filters help you make informed decisions.</p>
                <span class="feature-link">For Patients <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="feature-card" data-delay="200">
                <div class="feature-icon-wrapper"><i class="fas fa-chart-line"></i></div>
                <h3>Health Analytics</h3>
                <p>Access your complete medical history, past appointments, prescriptions, and doctor notes in one secure place.</p>
                <span class="feature-link">For Patients <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="feature-card" data-delay="300">
                <div class="feature-icon-wrapper"><i class="fas fa-user-md"></i></div>
                <h3>Doctor Management</h3>
                <p>Hospital admins can add, edit, or remove doctors. Manage their profiles, specializations, and consultation fees.</p>
                <span class="feature-link">For Hospitals <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="feature-card" data-delay="400">
                <div class="feature-icon-wrapper"><i class="fas fa-dollar-sign"></i></div>
                <h3>Payment Recording</h3>
                <p>Record payments manually after patient visits. Track revenue and generate detailed financial reports.</p>
                <span class="feature-link">For Hospitals <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="feature-card" data-delay="500">
                <div class="feature-icon-wrapper"><i class="fas fa-chart-pie"></i></div>
                <h3>Platform Reports</h3>
                <p>System admins can access comprehensive reports including appointments per hospital and busiest doctors.</p>
                <span class="feature-link">For Admins <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </div>
</section>

<!-- Role Cards Section -->
<section class="role-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">User Roles</span>
            <h2>Designed for Every Role</h2>
            <p>Tailored experiences for all healthcare system participants</p>
        </div>
        <div class="role-cards">
            <div class="role-card" data-delay="0">
                <div class="role-icon"><i class="fas fa-user-shield"></i></div>
                <h3>System Admin</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Manage all hospitals</li>
                    <li><i class="fas fa-check-circle"></i> Platform-wide reports</li>
                    <li><i class="fas fa-check-circle"></i> User account management</li>
                    <li><i class="fas fa-check-circle"></i> System oversight</li>
                </ul>
            </div>
            <div class="role-card" data-delay="100">
                <div class="role-icon"><i class="fas fa-hospital-user"></i></div>
                <h3>Hospital Admin</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Manage hospital profile</li>
                    <li><i class="fas fa-check-circle"></i> Add/Edit doctors</li>
                    <li><i class="fas fa-check-circle"></i> View all appointments</li>
                    <li><i class="fas fa-check-circle"></i> Record payments</li>
                </ul>
            </div>
            <div class="role-card" data-delay="200">
                <div class="role-icon"><i class="fas fa-user-md"></i></div>
                <h3>Doctor</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Manage schedule</li>
                    <li><i class="fas fa-check-circle"></i> View patient details</li>
                    <li><i class="fas fa-check-circle"></i> Confirm/Cancel appointments</li>
                    <li><i class="fas fa-check-circle"></i> Add medical notes</li>
                </ul>
            </div>
            <div class="role-card" data-delay="300">
                <div class="role-icon"><i class="fas fa-user-injured"></i></div>
                <h3>Patient</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Search doctors/hospitals</li>
                    <li><i class="fas fa-check-circle"></i> Book appointments</li>
                    <li><i class="fas fa-check-circle"></i> View medical history</li>
                    <li><i class="fas fa-check-circle"></i> Cancel appointments</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section - Real Data from Database -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-delay="0">
                <div class="stat-number counter" data-target="{{ $stats['hospitals'] }}">0</div>
                <div class="stat-label">Hospitals Connected</div>
            </div>
            <div class="stat-item" data-delay="100">
                <div class="stat-number counter" data-target="{{ $stats['doctors'] }}">0</div>
                <div class="stat-label">Qualified Doctors</div>
            </div>
            <div class="stat-item" data-delay="200">
                <div class="stat-number counter" data-target="{{ $stats['patients'] }}">0</div>
                <div class="stat-label">Happy Patients</div>
            </div>
            <div class="stat-item" data-delay="300">
                <div class="stat-number counter" data-target="{{ $stats['appointments'] }}">0</div>
                <div class="stat-label">Appointments Booked</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of satisfied patients and healthcare providers on MediBook today</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary btn-large">Create Free Account</a>
                <a href="{{ route('contact') }}" class="btn btn-outline btn-large">Contact Us</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations on scroll
    initScrollAnimations();
    
    // Initialize counter animation
    initCounters();
});

function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // If it's a counter stat, start counting
                if (entry.target.classList.contains('stat-item')) {
                    const counter = entry.target.querySelector('.counter');
                    if (counter && !counter.hasAttribute('data-counted')) {
                        counter.setAttribute('data-counted', 'true');
                        animateNumber(counter);
                    }
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    
    // Observe all animatable elements
    document.querySelectorAll('.feature-card, .role-card, .stat-item, .cta-card').forEach(el => {
        observer.observe(el);
        
        // Apply staggered delay if exists
        const delay = el.getAttribute('data-delay');
        if (delay) {
            el.style.transitionDelay = delay + 'ms';
        }
    });
}

function animateNumber(element) {
    const target = parseInt(element.getAttribute('data-target'));
    if (isNaN(target)) return;
    
    let current = 0;
    const increment = Math.ceil(target / 50);
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = current.toLocaleString();
        }
    }, 40);
}

function initCounters() {
    // For elements already visible on load
    document.querySelectorAll('.stat-item').forEach(stat => {
        const rect = stat.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            const counter = stat.querySelector('.counter');
            if (counter && !counter.hasAttribute('data-counted')) {
                counter.setAttribute('data-counted', 'true');
                animateNumber(counter);
            }
        }
    });
}
</script>
@endpush