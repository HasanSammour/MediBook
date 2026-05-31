@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, Dr. ' . $user->name)

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

        /* Appointments Table */
        .appointments-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .appointments-table th,
        .appointments-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .appointments-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            font-size: 0.85rem;
        }

        .appointments-table td {
            font-size: 0.85rem;
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .patient-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: none;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .action-btn.view {
            background: #f3f4f6;
            color: var(--dark-color);
        }

        .action-btn.confirm {
            background: #10b981;
            color: white;
        }

        .action-btn.complete {
            background: #3b82f6;
            color: white;
        }

        .action-btn.cancel {
            background: #ef4444;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.85;
        }

        /* Weekly Schedule Card */
        .schedule-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .schedule-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .schedule-header h3 {
            font-size: 1rem;
            margin-bottom: 0;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.75rem;
        }

        .schedule-day {
            background: #f9fafb;
            border-radius: 12px;
            padding: 0.75rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .schedule-day:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .schedule-day-name {
            font-weight: 700;
            font-size: 0.8rem;
            margin-bottom: 0.4rem;
        }

        .schedule-day-hours {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-bottom: 0.4rem;
        }

        .schedule-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 600;
        }

        .status-working {
            background: #d1fae5;
            color: #065f46;
        }

        .status-off {
            background: #fee2e2;
            color: #991b1b;
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

        /* Loading */
        .loading-spinner {
            text-align: center;
            padding: 20px;
        }

        .loading-spinner i {
            font-size: 1.5rem;
            color: var(--primary-color);
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

        .empty-state {
            text-align: center;
            padding: 2rem;
        }

        .empty-state i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
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

            .schedule-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .schedule-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .appointments-table th,
            .appointments-table td {
                padding: 0.75rem;
                font-size: 0.75rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .schedule-grid {
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
            <h2>Today's Schedule</h2>
            <p id="todayCount">Loading appointments...</p>
        </div>
        <a href="{{ route('doctor.schedule') }}" class="welcome-btn">
            View Full Schedule <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Today's Appointments -->
    <div class="section-title">
        <h3>Today's Appointments</h3>
        <a href="{{ route('doctor.appointments.index') }}">View All →</a>
    </div>

    <div class="appointments-table-container" id="appointmentsContainer">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i> Loading appointments...
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="schedule-card">
        <div class="schedule-header">
            <h3><i class="fas fa-calendar-week"></i> This Week's Schedule</h3>
            <a href="{{ route('doctor.schedule') }}">Edit Schedule →</a>
        </div>
        <div id="scheduleContainer">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Loading schedule...
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-title">
        <h3>Quick Actions</h3>
    </div>
    <div class="quick-actions">
        <a href="{{ route('doctor.appointments.index') }}" class="quick-card">
            <i class="fas fa-calendar-alt"></i>
            <span>My Appointments</span>
        </a>
        <a href="{{ route('doctor.schedule') }}" class="quick-card">
            <i class="fas fa-clock"></i>
            <span>My Schedule</span>
        </a>
        <a href="{{ route('doctor.patients.index') }}" class="quick-card">
            <i class="fas fa-users"></i>
            <span>Patient History</span>
        </a>
        <a href="{{ route('doctor.profile.edit') }}" class="quick-card">
            <i class="fas fa-user-cog"></i>
            <span>Profile Settings</span>
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadStats();
            loadTodayAppointments();
            loadWeeklySchedule();

            // Availability toggle
            const availabilityToggle = document.getElementById('availabilityToggle');
            if (availabilityToggle) {
                availabilityToggle.addEventListener('change', async function () {
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch('{{ route("doctor.schedule.availability") }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        Swal.fire({
                            icon: 'success',
                            title: data.is_available ? 'You are now available' : 'You are now unavailable',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } catch (error) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            }
        });

        async function loadStats() {
            const statsGrid = document.getElementById('statsGrid');

            try {
                const response = await fetch('{{ route("doctor.dashboard.stats") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    statsGrid.innerHTML = `
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="stat-value">${data.stats.today_appointments}</div>
                                <div class="stat-label">Today's Appointments</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-users"></i></div>
                                <div class="stat-value">${data.stats.total_patients}</div>
                                <div class="stat-label">Total Patients</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="stat-value">${data.stats.completed_today}</div>
                                <div class="stat-label">Completed Today</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-star"></i></div>
                                <div class="stat-value">${data.stats.rating}</div>
                                <div class="stat-label">Rating</div>
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

        async function loadTodayAppointments() {
            const container = document.getElementById('appointmentsContainer');

            container.innerHTML = `
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i> Loading appointments...
                    </div>
                `;

            try {
                const response = await fetch('{{ route("doctor.dashboard.appointments.today") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                document.getElementById('todayCount').innerText = `You have ${data.appointments.length} appointment(s) scheduled for today`;

                if (data.success && data.appointments.length > 0) {
                    let html = `
                            <table class="appointments-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                    data.appointments.forEach(apt => {
                        const time = new Date(apt.appointment_date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                        const patientName = apt.patient?.name || 'Patient';
                        const patientInitial = patientName.charAt(0);
                        const patientAge = apt.patient?.age || 'N/A';
                        const patientGender = apt.patient?.gender || 'N/A';
                        const patientPhone = apt.patient?.phone || 'N/A';
                        const patientEmail = apt.patient?.email || 'N/A';
                        const statusClass = apt.status === 'confirmed' ? 'status-confirmed' : 'status-pending';
                        const statusText = apt.status === 'confirmed' ? 'Confirmed' : 'Pending';
                        const reason = apt.patient_notes ? apt.patient_notes.substring(0, 50) : 'General Checkup';

                        html += `
                                <tr>
                                    <td><strong>${escapeHtml(time)}</strong></td>
                                    <td>
                                        <div class="patient-cell">
                                            <div class="patient-avatar">${escapeHtml(patientInitial)}</div>
                                            <span>${escapeHtml(patientName)}</span>
                                        </div>
                                    </td>
                                    <td>${escapeHtml(patientAge)}</td>
                                    <td>${capitalize(patientGender)}</td>
                                    <td>${escapeHtml(reason)}</td>
                                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn view" onclick="viewPatient(${apt.patient_id}, '${escapeHtml(patientName)}', '${escapeHtml(patientPhone)}', '${escapeHtml(patientEmail)}', '${patientAge}', '${patientGender}')">View</button>
                                            ${apt.status === 'confirmed' ? `<button class="action-btn complete" onclick="completeAppointment(${apt.id})">Complete</button>` : ''}
                                            ${apt.status === 'pending' ? `<button class="action-btn confirm" onclick="confirmAppointment(${apt.id})">Confirm</button>` : ''}
                                            <button class="action-btn cancel" onclick="cancelAppointment(${apt.id})">Cancel</button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                    });

                    html += `</tbody>赶80</div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No appointments scheduled for today</p></div>';
                }
            } catch (error) {
                console.error('Error loading appointments:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-times"></i><p>No appointments scheduled for today</p></div>';
            }
        }

        async function loadWeeklySchedule() {
            const container = document.getElementById('scheduleContainer');

            container.innerHTML = `
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i> Loading schedule...
                    </div>
                `;

            try {
                const response = await fetch('{{ route("doctor.schedule.working-hours") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    let html = '<div class="schedule-grid">';
                    data.schedule.forEach(day => {
                        html += `
                                <div class="schedule-day">
                                    <div class="schedule-day-name">${day.day}</div>
                                    <div class="schedule-day-hours">${day.hours}</div>
                                    <span class="schedule-status ${day.available ? 'status-working' : 'status-off'}">
                                        ${day.available ? 'Working' : 'Off'}
                                    </span>
                                </div>
                            `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-week"></i><p>Schedule not available</p></div>';
                }
            } catch (error) {
                console.error('Error loading schedule:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-calendar-week"></i><p>Schedule not available</p></div>';
            }
        }

        function viewPatient(id, name, phone, email, age, gender) {
            Swal.fire({
                title: `<div style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                                <i class="fas fa-user-circle" style="font-size: 2rem; color: #2563eb;"></i>
                                <span>${escapeHtml(name)}</span>
                            </div>`,
                html: `
                        <div style="text-align: left; margin-top: 1rem;">
                            <div style="background: #f9fafb; padding: 12px; border-radius: 12px; margin-bottom: 1rem;">
                                <p><strong><i class="fas fa-phone"></i> Phone:</strong> ${escapeHtml(phone || 'N/A')}</p>
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${escapeHtml(email || 'N/A')}</p>
                                <p><strong><i class="fas fa-calendar"></i> Age:</strong> ${age || 'N/A'} years</p>
                                <p><strong><i class="fas fa-venus-mars"></i> Gender:</strong> ${gender ? capitalize(gender) : 'N/A'}</p>
                            </div>
                        </div>
                    `,
                icon: 'info',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Close',
                width: '450px'
            });
        }

        function capitalize(str) {
            if (!str) return 'N/A';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function confirmAppointment(id) {
            Swal.fire({
                title: 'Confirm Appointment',
                text: 'Are you sure you want to confirm this appointment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, confirm'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/doctor/appointments/${id}/confirm`, {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Confirmed!', 'Appointment confirmed.', 'success');
                            loadTodayAppointments();
                            loadStats();
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                }
            });
        }

        function completeAppointment(id) {
            Swal.fire({
                title: 'Complete Appointment',
                text: 'Add medical notes for this appointment:',
                input: 'textarea',
                inputPlaceholder: 'Enter diagnosis, prescription, and other notes...',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/doctor/appointments/${id}/complete`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ notes: result.value })
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Completed!', 'Appointment completed successfully.', 'success');
                            loadTodayAppointments();
                            loadStats();
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                }
            });
        }

        function cancelAppointment(id) {
            Swal.fire({
                title: 'Cancel Appointment',
                text: 'Are you sure you want to cancel this appointment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No, keep it'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/doctor/appointments/${id}/cancel`, {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Cancelled!', 'Appointment cancelled successfully.', 'success');
                            loadTodayAppointments();
                            loadStats();
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                }
            });
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