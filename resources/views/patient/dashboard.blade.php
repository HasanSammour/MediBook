@extends('layouts.patient')

@section('title', 'Patient Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name)

@push('styles')
    <style>
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        /* Loading Skeleton for Stats */
        .stat-skeleton {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .skeleton-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            margin-bottom: 1rem;
            animation: shimmer 1.5s infinite;
        }

        .skeleton-line {
            height: 28px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            width: 70%;
            animation: shimmer 1.5s infinite;
        }

        .skeleton-line-small {
            height: 14px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            width: 50%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            padding: 1.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-text h2 {
            font-size: 1.3rem;
            margin-bottom: 0.25rem;
            color: white;
        }

        .welcome-text p {
            opacity: 0.9;
            font-size: 0.85rem;
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .welcome-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .welcome-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Section Title */
        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title h3 {
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .section-title a {
            font-size: 0.8rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Appointments List */
        .appointments-list {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .appointment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .appointment-item:hover {
            background: #fafafa;
        }

        .appointment-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 2;
        }

        .doctor-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .appointment-details h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .appointment-details p {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .appointment-meta {
            flex: 1;
            text-align: left;
        }

        .appointment-date {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .appointment-time {
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Two Column Layout */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Medical Notes Card */
        .health-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .health-card h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }

        .note-item {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .note-doctor {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .note-date {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
        }

        .note-text {
            font-size: 0.75rem;
            color: var(--dark-color);
            line-height: 1.5;
        }

        /* Payments List */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .payment-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .payment-info i {
            width: 30px;
            color: var(--secondary-color);
            font-size: 1rem;
        }

        .payment-details h4 {
            font-size: 0.85rem;
            margin-bottom: 0.2rem;
        }

        .payment-details p {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .payment-amount {
            font-weight: 700;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .quick-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .quick-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-light);
        }

        .quick-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .quick-card span {
            color: var(--dark-color);
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 40px;
            background: var(--white);
            border-radius: 20px;
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 10px;
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
        }

        .empty-state i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
            display: block;
        }

        .empty-state p {
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .two-columns {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .appointment-item {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .appointment-info {
                flex-direction: column;
            }

            .appointment-meta {
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Stats Cards with Skeleton Loading -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-icon"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line-small"></div>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-text">
            <h2>Need to see a doctor?</h2>
            <p>Book an appointment with top specialists in your area</p>
        </div>
        <a href="{{ route('patient.search-doctors') }}" class="welcome-btn">
            Book Now <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="section-title">
        <h3>Upcoming Appointments</h3>
        <a href="{{ route('patient.appointments.index') }}">View All →</a>
    </div>

    <div id="upcomingAppointments">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading appointments...</div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-columns">
        <!-- Recent Medical Notes -->
        <div class="health-card">
            <h3>Recent Medical Notes</h3>
            <div id="medicalNotes">
                <div class="loading-container" style="padding: 20px;">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading notes...</div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="health-card">
            <h3>Recent Payments</h3>
            <div id="recentPayments">
                <div class="loading-container" style="padding: 20px;">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading payments...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-title">
        <h3>Quick Actions</h3>
    </div>
    <div class="quick-actions">
        <a href="{{ route('patient.search-doctors') }}" class="quick-card">
            <i class="fas fa-search"></i>
            <span>Find a Doctor</span>
        </a>
        <a href="{{ route('patient.appointments.index') }}" class="quick-card">
            <i class="fas fa-calendar-alt"></i>
            <span>My Appointments</span>
        </a>
        <a href="{{ route('patient.profile.show') }}" class="quick-card">
            <i class="fas fa-user-edit"></i>
            <span>Update Profile</span>
        </a>
        <a href="{{ route('patient.medical-history') }}" class="quick-card">
            <i class="fas fa-history"></i>
            <span>Medical History</span>
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadStats();
            loadUpcomingAppointments();
            loadMedicalNotes();
            loadRecentPayments();
        });

        async function loadStats() {
            const statsGrid = document.getElementById('statsGrid');

            try {
                const response = await fetch('{{ route("patient.dashboard.stats") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    statsGrid.innerHTML = `
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="stat-value">${data.stats.upcoming}</div>
                                <div class="stat-label">Upcoming Appointments</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                                <div class="stat-value">${data.stats.doctors_visited}</div>
                                <div class="stat-label">Doctors Visited</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                                <div class="stat-value">$${data.stats.total_spent.toLocaleString()}</div>
                                <div class="stat-label">Total Spent</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="stat-value">${data.stats.completed}</div>
                                <div class="stat-label">Completed Visits</div>
                            </div>
                        `;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
                statsGrid.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                            <div class="stat-value">Error</div>
                            <div class="stat-label">Failed to load</div>
                        </div>
                    `.repeat(4);
            }
        }

        async function loadUpcomingAppointments() {
            const container = document.getElementById('upcomingAppointments');

            container.innerHTML = `
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Loading appointments...</div>
                    </div>
                `;

            try {
                const response = await fetch('{{ route("patient.dashboard.appointments.upcoming") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.appointments && data.appointments.length > 0) {
                    let html = '<div class="appointments-list">';
                    data.appointments.forEach(apt => {
                        const statusClass = apt.status === 'confirmed' ? 'status-confirmed' : 'status-pending';
                        const statusText = apt.status === 'confirmed' ? 'Confirmed' : 'Pending';
                        const date = new Date(apt.appointment_date);
                        const doctorName = apt.doctor?.name || 'Doctor';
                        const doctorInitial = doctorName.charAt(0);
                        const doctorSpecialty = apt.doctor?.specialization || 'General';
                        const hospitalName = apt.hospital?.name || 'Hospital';

                        html += `
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-avatar">${escapeHtml(doctorInitial)}</div>
                                        <div class="appointment-details">
                                            <h4>${escapeHtml(doctorName)}</h4>
                                            <p>${escapeHtml(doctorSpecialty)} • ${escapeHtml(hospitalName)}</p>
                                        </div>
                                    </div>
                                    <div class="appointment-meta">
                                        <div class="appointment-date">${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</div>
                                        <div class="appointment-time">${date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</div>
                                        <span class="status-badge ${statusClass}">${statusText}</span>
                                    </div>
                                </div>
                            `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No upcoming appointments</p></div>';
                }
            } catch (error) {
                console.error('Error loading upcoming appointments:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No upcoming appointments</p></div>';
            }
        }

        async function loadMedicalNotes() {
            const container = document.getElementById('medicalNotes');

            container.innerHTML = `
                    <div class="loading-container" style="padding: 20px;">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Loading notes...</div>
                    </div>
                `;

            try {
                const response = await fetch('{{ route("patient.dashboard.medical-notes") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.notes && data.notes.length > 0) {
                    let html = '';
                    data.notes.forEach(note => {
                        const doctorName = note.doctor?.name || 'Doctor';
                        const noteDate = new Date(note.updated_at).toLocaleDateString();
                        const noteText = note.notes ? (note.notes.length > 100 ? note.notes.substring(0, 100) + '...' : note.notes) : 'No notes available';

                        html += `
                                <div class="note-item">
                                    <div class="note-doctor">${escapeHtml(doctorName)}</div>
                                    <div class="note-date">${noteDate}</div>
                                    <div class="note-text">${escapeHtml(noteText)}</div>
                                </div>
                            `;
                    });
                    html += `<div style="margin-top: 1rem;"><a href="{{ route('patient.medical-history') }}" style="font-size: 0.75rem; color: var(--primary-color); text-decoration: none;">View All Notes →</a></div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-notes-medical"></i><p>No medical notes available</p></div>';
                }
            } catch (error) {
                console.error('Error loading medical notes:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-notes-medical"></i><p>No medical notes available</p></div>';
            }
        }

        async function loadRecentPayments() {
            const container = document.getElementById('recentPayments');

            container.innerHTML = `
                    <div class="loading-container" style="padding: 20px;">
                        <div class="loading-spinner"></div>
                        <div class="loading-text">Loading payments...</div>
                    </div>
                `;

            try {
                const response = await fetch('{{ route("patient.dashboard.payments.recent") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.payments && data.payments.length > 0) {
                    let html = '';
                    data.payments.forEach(payment => {
                        const doctorName = payment.appointment?.doctor?.name || 'Doctor';
                        const paymentDate = new Date(payment.payment_date).toLocaleDateString();
                        const amount = parseFloat(payment.amount).toFixed(2);

                        html += `
                                <div class="payment-item">
                                    <div class="payment-info">
                                        <i class="fas fa-receipt"></i>
                                        <div class="payment-details">
                                            <h4>Consultation - ${escapeHtml(doctorName)}</h4>
                                            <p>${paymentDate}</p>
                                        </div>
                                    </div>
                                    <div class="payment-amount">$${amount}</div>
                                </div>
                            `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-receipt"></i><p>No payment records</p></div>';
                }
            } catch (error) {
                console.error('Error loading recent payments:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-receipt"></i><p>No payment records</p></div>';
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
    </script>
@endpush