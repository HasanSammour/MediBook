@extends('layouts.guest')

@section('title', 'Contact Us - MediBook')

@push('styles')
    <style>
        /* ============================================
           PAGE HEADER
        ============================================ */
        .page-header {
            padding: 100px 0 50px;
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
            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(40px, 40px);
            }
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

        /* ============================================
           CONTACT SECTION - EQUAL HEIGHT
        ============================================ */
        .contact-section {
            padding: 60px 0;
            background: var(--white);
        }

        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            max-width: 1200px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        /* Left Side - Info Section */
        .info-side {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 2.5rem;
            color: white;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .info-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            width: fit-content;
        }

        .info-side h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: white;
        }

        .info-side>p {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .info-cards {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-bottom: 2rem;
            flex: 1;
        }

        .info-card-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .info-card-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 1.2rem;
            color: white;
        }

        .info-content h3 {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
            color: white;
        }

        .info-content p,
        .info-content a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        .info-content a:hover {
            color: white;
        }

        /* Social Links */
        .social-side {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .social-side h3 {
            font-size: 0.9rem;
            margin-bottom: 1rem;
            color: white;
        }

        .social-icons-side {
            display: flex;
            gap: 0.75rem;
        }

        .social-icon-side {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
        }

        .social-icon-side:hover {
            background: white;
            color: #2563eb;
            transform: translateY(-3px);
        }

        /* Right Side - Form Section */
        .form-side {
            padding: 2.5rem;
            background: var(--white);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .form-side h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .form-side>p {
            color: var(--gray-color);
            margin-bottom: 1.5rem;
        }

        .contact-form {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--dark-color);
        }

        .form-group label .required {
            color: var(--danger-color);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            font-family: inherit;
            background: #f9fafb;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-submit i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .btn-submit:hover i {
            transform: translateX(5px);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* ============================================
           MAP SECTION
        ============================================ */
        .map-section {
            padding: 0 0 60px;
        }

        .map-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 24px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .map-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .map-placeholder {
            height: 280px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .map-placeholder::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: moveDots 20s linear infinite;
        }

        @keyframes moveDots {
            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(40px, 40px);
            }
        }

        .map-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .map-card:hover .map-icon {
            transform: scale(1.1);
            background: #2563eb;
        }

        .map-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .map-placeholder h3 {
            color: white;
            margin-bottom: 0.25rem;
            font-size: 1.2rem;
            z-index: 1;
        }

        .map-placeholder p {
            opacity: 0.8;
            font-size: 0.8rem;
            z-index: 1;
        }

        /* ============================================
           FAQ SECTION
        ============================================ */
        .faq-section {
            padding: 60px 0;
            background: #f8fafc;
        }

        .faq-grid {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--white);
            border-radius: 16px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .faq-item:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: rgba(37, 99, 235, 0.02);
        }

        .faq-question h3 {
            font-size: 0.95rem;
            margin-bottom: 0;
            font-weight: 600;
        }

        .faq-question i {
            color: var(--gray-color);
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
            color: var(--primary-color);
        }

        .faq-answer {
            padding: 0 1.25rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 0 1.25rem 1rem 1.25rem;
            max-height: 300px;
        }

        .faq-answer p {
            color: var(--gray-color);
            line-height: 1.6;
            margin: 0;
            font-size: 0.85rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            margin-bottom: 0.5rem;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 968px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .contact-wrapper {
                grid-template-columns: 1fr;
                border-radius: 24px;
                margin: 0 20px;
            }

            .info-side {
                padding: 2rem;
            }

            .form-side {
                padding: 2rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 80px 0 40px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .info-side h2,
            .form-side h2 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .info-cards {
                gap: 1rem;
            }

            .info-card-item {
                padding: 0.6rem;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .contact-wrapper {
                margin: 0 16px;
            }

            .info-side,
            .form-side {
                padding: 1.5rem;
            }

            .info-icon {
                width: 38px;
                height: 38px;
            }

            .info-icon i {
                font-size: 1rem;
            }

            .faq-question h3 {
                font-size: 0.85rem;
            }

            .map-placeholder {
                height: 240px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Get in <span class="highlight">Touch</span></h1>
            <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible</p>
        </div>
    </section>

    <!-- Contact Section - Equal Height Columns -->
    <section class="contact-section">
        <div class="contact-wrapper">
            <!-- Left Side - Info Section (Blue Background) -->
            <div class="info-side">
                <span class="info-badge">Contact Information</span>
                <h2>Let's Talk</h2>
                <p>We're here to help and answer any questions you might have. We look forward to hearing from you.</p>

                <div class="info-cards">
                    <div class="info-card-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-content">
                            <h3>Visit Us</h3>
                            <p>123 Healthcare Street, Medical City, Gaza, Palestine</p>
                        </div>
                    </div>
                    <div class="info-card-item">
                        <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-content">
                            <h3>Call Us</h3>
                            <p><a href="tel:+97082838888">+970 (8) 283-8888</a> | <a href="tel:+97082824444">+970 (8)
                                    282-4444</a></p>
                        </div>
                    </div>
                    <div class="info-card-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="info-content">
                            <h3>Email Us</h3>
                            <p><a href="mailto:info@medibook.com">info@medibook.com</a> | <a
                                    href="mailto:support@medibook.com">support@medibook.com</a></p>
                        </div>
                    </div>
                    <div class="info-card-item">
                        <div class="info-icon"><i class="fas fa-clock"></i></div>
                        <div class="info-content">
                            <h3>Working Hours</h3>
                            <p>Mon-Fri: 9:00 AM - 8:00 PM | Sat: 10:00 AM - 4:00 PM | Sun: Closed</p>
                        </div>
                    </div>
                </div>

                <div class="social-side">
                    <h3>Follow Us</h3>
                    <div class="social-icons-side">
                        <a href="#" class="social-icon-side"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon-side"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon-side"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon-side"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon-side"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form Section (White Background) -->
            <div class="form-side">
                <h2>Send Us a Message</h2>
                <p>Fill out the form below and we'll get back to you within 24 hours</p>

                <form id="contactForm" class="contact-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" id="fullName" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" id="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" id="phone" placeholder="Enter your phone number">
                        </div>
                        <div class="form-group">
                            <label>Subject <span class="required">*</span></label>
                            <select id="subject" required>
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="hospital">Hospital Partnership</option>
                                <option value="doctor">Doctor Registration</option>
                                <option value="billing">Billing Question</option>
                                <option value="feedback">Feedback</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Message <span class="required">*</span></label>
                        <textarea id="message" rows="4" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                        Send Message <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-card" id="mapCard">
                <div class="map-placeholder">
                    <div class="map-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>MediBook Headquarters</h3>
                    <p>123 Healthcare Street, Medical City, Gaza, Palestine</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">FAQ</span>
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about MediBook</p>
            </div>
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do I book an appointment?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Register as a patient, search for doctors, select your time slot, and confirm. You'll receive a
                            confirmation email instantly.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is my medical information secure?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! We use bank-grade encryption to protect all your data. Our platform is fully secure and
                            follows strict privacy protocols.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I cancel or reschedule?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can cancel up to 24 hours before the scheduled time without penalty. Go to "My
                            Appointments" to manage it.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do I become a partner hospital?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Contact our System Admin team at admin@medibook.com. They will guide you through the onboarding
                            process.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How can doctors join MediBook?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Doctors are added by Hospital Administrators. Contact the hospital where you work to get listed.
                        </p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What payment methods are accepted?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We accept cash, card, and insurance. Payments are recorded by hospital admins after your visit.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initFaqAccordion();
            initContactForm();
            initMapClick();
        });

        function initFaqAccordion() {
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('.faq-question');
                if (question) {
                    question.addEventListener('click', () => {
                        document.querySelectorAll('.faq-item').forEach(other => {
                            if (other !== item && other.classList.contains('active')) {
                                other.classList.remove('active');
                            }
                        });
                        item.classList.toggle('active');
                    });
                }
            });
        }

        function initMapClick() {
            const mapCard = document.getElementById('mapCard');
            if (mapCard) {
                mapCard.addEventListener('click', () => {
                    Swal.fire({
                        title: '📍 MediBook Headquarters',
                        html: `
                        <div style="text-align: center;">
                            <p><strong>123 Healthcare Street</strong></p>
                            <p>Medical City, Gaza, Palestine</p>
                            <hr style="margin: 1rem 0;">
                            <p style="font-size: 0.85rem; color: #6b7280;">Full Google Maps integration coming soon!</p>
                        </div>
                    `,
                        icon: 'info',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Close'
                    });
                });
            }
        }

        function initContactForm() {
            const form = document.getElementById('contactForm');
            if (!form) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const fullName = document.getElementById('fullName').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value.trim();

                if (!fullName) {
                    Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please enter your full name.', confirmButtonColor: '#2563eb' });
                    return;
                }

                if (!email) {
                    Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please enter your email address.', confirmButtonColor: '#2563eb' });
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email address.', confirmButtonColor: '#2563eb' });
                    return;
                }

                if (!subject) {
                    Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please select a subject.', confirmButtonColor: '#2563eb' });
                    return;
                }

                if (!message) {
                    Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please enter your message.', confirmButtonColor: '#2563eb' });
                    return;
                }

                if (message.length < 10) {
                    Swal.fire({ icon: 'error', title: 'Message Too Short', text: 'Message must be at least 10 characters.', confirmButtonColor: '#2563eb' });
                    return;
                }

                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

                try {
                    const response = await fetch('{{ route("contact.submit") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name: fullName, email: email, phone: phone, subject: subject, message: message })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Message Sent!',
                            text: data.message,
                            confirmButtonColor: '#2563eb',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        form.reset();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Please try again.', confirmButtonColor: '#2563eb' });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Network Error', text: 'Please check your connection.', confirmButtonColor: '#2563eb' });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    </script>
@endpush