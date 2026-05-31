@extends('layouts.doctor')

@section('title', 'My Appointments')

@section('page-title', 'My Appointments')
@section('page-subtitle', 'View and manage all your appointments')

@push('styles')
    <style>
        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            background: var(--white);
            padding: 0.5rem;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filter-btn {
            padding: 0.6rem 1.5rem;
            border: none;
            background: transparent;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--gray-color);
        }

        .filter-btn:hover {
            background: rgba(37, 99, 235, 0.05);
            color: var(--primary-color);
        }

        .filter-btn.active {
            background: var(--primary-color);
            color: white;
        }

        /* Search Bar */
        .search-bar {
            margin-bottom: 1.5rem;
        }

        .search-input {
            position: relative;
            max-width: 350px;
        }

        .search-input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }

        .search-input input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Results Info */
        .results-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        .results-count span {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Appointments Table */
        .appointments-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
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

        /* Patient Cell */
        .patient-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .patient-avatar svg {
            width: 40px;
            height: 40px;
        }

        .patient-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .patient-phone {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-btn {
            background: none;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .action-btn.view {
            background: #f3f4f6;
            color: var(--dark-color);
        }

        .action-btn.view:hover {
            background: #e5e7eb;
        }

        .action-btn.confirm {
            background: #10b981;
            color: white;
        }

        .action-btn.confirm:hover {
            background: #059669;
        }

        .action-btn.complete {
            background: #3b82f6;
            color: white;
        }

        .action-btn.complete:hover {
            background: #2563eb;
        }

        .action-btn.cancel {
            background: #ef4444;
            color: white;
        }

        .action-btn.cancel:hover {
            background: #dc2626;
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 60px;
        }

        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid var(--primary-color);
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
            padding: 60px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            background: var(--white);
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            color: var(--dark-color);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: rgba(37, 99, 235, 0.05);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-tabs {
                gap: 0.3rem;
            }

            .filter-btn {
                padding: 0.4rem 1rem;
                font-size: 0.75rem;
            }

            .search-input {
                max-width: 100%;
            }

            .results-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .appointments-table th,
            .appointments-table td {
                padding: 0.75rem;
                font-size: 0.75rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-btn active" data-status="all">All Appointments</button>
        <button class="filter-btn" data-status="upcoming">Upcoming</button>
        <button class="filter-btn" data-status="today">Today</button>
        <button class="filter-btn" data-status="completed">Completed</button>
        <button class="filter-btn" data-status="cancelled">Cancelled</button>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by patient name...">
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info" id="resultsInfo" style="display: none;">
        <div class="results-count">
            Showing <span id="resultsFrom">0</span> to <span id="resultsTo">0</span> of <span id="resultsTotal">0</span>
            results
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="appointments-table-container" id="tableContainer">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading appointments...</div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper" id="paginationWrapper" style="display: none;">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-container" id="paginationContainer"></div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentStatus = 'all';
        let currentSearch = '';
        let currentPage = 1;
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function () {
            loadAppointments();

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentStatus = this.getAttribute('data-status');
                    currentPage = 1;
                    loadAppointments();
                });
            });

            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadAppointments();
                }, 500);
            });
        });

        async function loadAppointments() {
            const tableContainer = document.getElementById('tableContainer');
            const resultsInfo = document.getElementById('resultsInfo');
            const paginationWrapper = document.getElementById('paginationWrapper');

            tableContainer.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading appointments...</div>
            </div>
        `;
            resultsInfo.style.display = 'none';
            paginationWrapper.style.display = 'none';

            try {
                const params = new URLSearchParams();
                params.append('status', currentStatus);
                if (currentSearch) params.append('search', currentSearch);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('doctor.appointments.data') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    renderTable(data);
                    resultsInfo.style.display = 'flex';
                    paginationWrapper.style.display = 'flex';

                    document.getElementById('resultsFrom').innerText = data.from || 0;
                    document.getElementById('resultsTo').innerText = data.to || 0;
                    document.getElementById('resultsTotal').innerText = data.total;
                    document.getElementById('paginationInfo').innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;

                    renderPagination(data);
                }
            } catch (error) {
                console.error('Error:', error);
                tableContainer.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Error loading appointments</h3><p>Please try again later.</p></div>`;
            }
        }

        function renderTable(data) {
            const tableContainer = document.getElementById('tableContainer');

            if (data.data.length === 0) {
                tableContainer.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-times"></i><h3>No appointments found</h3><p>Try adjusting your filters.</p></div>`;
                return;
            }

            let html = `
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

            data.data.forEach(apt => {
                const date = new Date(apt.appointment_date);
                const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                const patientName = apt.patient?.name || 'Patient';
                const patientPhone = apt.patient?.phone || 'N/A';
                const patientAvatar = apt.patient?.avatar_html || `<div class="patient-avatar" style="display: flex; align-items: center; justify-content: center;"><span style="color: white; font-weight: 600;">${patientName.charAt(0)}</span></div>`;
                const statusClass = getStatusClass(apt.status);
                const statusText = apt.status.charAt(0).toUpperCase() + apt.status.slice(1);
                const canConfirm = apt.status === 'pending';
                const canComplete = apt.status === 'confirmed';
                const canCancel = apt.status === 'pending' || apt.status === 'confirmed';

                html += `
                <tr>
                    <td>
                        <div class="patient-cell">
                            <div class="patient-avatar">
                                ${patientAvatar}
                            </div>
                            <div>
                                <div class="patient-name">${escapeHtml(patientName)}</div>
                                <div class="patient-phone">${escapeHtml(patientPhone)}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>${formattedDate}</div>
                        <div style="font-size: 0.7rem; color: var(--gray-color);">${formattedTime}</div>
                    </td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="viewAppointment(${apt.id})">View Details</button>
                            ${canConfirm ? `<button class="action-btn confirm" onclick="confirmAppointment(${apt.id})">Confirm</button>` : ''}
                            ${canComplete ? `<button class="action-btn complete" onclick="completeAppointment(${apt.id})">Complete</button>` : ''}
                            ${canCancel ? `<button class="action-btn cancel" onclick="cancelAppointment(${apt.id})">Cancel</button>` : ''}
                        </div>
                    </td>
                </tr>
            `;
            });

            html += `
                </tbody>
            </table>
        `;

            tableContainer.innerHTML = html;
        }

        function getStatusClass(status) {
            switch (status) {
                case 'pending': return 'status-pending';
                case 'confirmed': return 'status-confirmed';
                case 'completed': return 'status-completed';
                case 'cancelled': return 'status-cancelled';
                default: return 'status-pending';
            }
        }

        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');

            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination">';

            if (data.current_page > 1) {
                html += `<li class="page-item"><button class="page-link" onclick="goToPage(${data.current_page - 1})">«</button></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">«</span></li>`;
            }

            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    html += `<li class="page-item"><button class="page-link" onclick="goToPage(${i})">${i}</button></li>`;
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            if (data.current_page < data.last_page) {
                html += `<li class="page-item"><button class="page-link" onclick="goToPage(${data.current_page + 1})">»</button></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">»</span></li>`;
            }

            html += '</ul>';
            container.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadAppointments();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        async function viewAppointment(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch(`/doctor/appointments/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    const apt = data.appointment;
                    Swal.fire({
                        title: 'Appointment Details',
                        html: `
                        <div style="text-align: left;">
                            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                <div class="patient-avatar" style="width: 80px; height: 80px;">
                                    ${apt.patient_avatar_html || `<div style="width: 100%; height: 100%; background: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 2rem; font-weight: 600;">${apt.patient_name.charAt(0)}</span></div>`}
                                </div>
                            </div>
                            <p><strong><i class="fas fa-user"></i> Patient:</strong> ${escapeHtml(apt.patient_name)}</p>
                            <p><strong><i class="fas fa-venus-mars"></i> Gender:</strong> ${escapeHtml(apt.patient_gender)}</p>
                            <p><strong><i class="fas fa-birthday-cake"></i> Age:</strong> ${apt.patient_age}</p>
                            <p><strong><i class="fas fa-phone"></i> Phone:</strong> ${escapeHtml(apt.patient_phone)}</p>
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${escapeHtml(apt.patient_email)}</p>
                            <p><strong><i class="fas fa-calendar"></i> Date:</strong> ${apt.date}</p>
                            <p><strong><i class="fas fa-clock"></i> Time:</strong> ${apt.time}</p>
                            <p><strong><i class="fas fa-hospital"></i> Hospital:</strong> ${escapeHtml(apt.hospital_name)}</p>
                            <p><strong><i class="fas fa-dollar-sign"></i> Fee:</strong> $${apt.fee}</p>
                            <hr>
                            <p><strong><i class="fas fa-user-md"></i> Patient's Notes:</strong></p>
                            <p style="background: #f9fafb; padding: 10px; border-radius: 8px;">${escapeHtml(apt.patient_notes)}</p>
                            <p><strong><i class="fas fa-stethoscope"></i> Your Medical Notes:</strong></p>
                            <p style="background: #f9fafb; padding: 10px; border-radius: 8px;">${escapeHtml(apt.medical_notes)}</p>
                        </div>
                    `,
                        icon: 'info',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Close',
                        width: '500px'
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Could not load appointment details.', 'error');
            }
        }

        async function confirmAppointment(id) {
            const result = await Swal.fire({
                title: 'Confirm Appointment?',
                text: 'Are you sure you want to confirm this appointment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, confirm',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                try {
                    const response = await fetch(`/doctor/appointments/${id}/confirm`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Confirmed!', 'Appointment confirmed successfully.', 'success');
                        loadAppointments();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            }
        }

        async function completeAppointment(id) {
            const { value: notes } = await Swal.fire({
                title: 'Complete Appointment',
                text: 'Add medical notes for this patient:',
                input: 'textarea',
                inputPlaceholder: 'Enter diagnosis, prescription, recommendations...',
                inputAttributes: { 'aria-label': 'Medical notes' },
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value || value.length < 5) {
                        return 'Please enter medical notes (minimum 5 characters)';
                    }
                }
            });

            if (notes) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                try {
                    const response = await fetch(`/doctor/appointments/${id}/complete`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ notes: notes })
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Completed!', 'Appointment marked as completed.', 'success');
                        loadAppointments();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            }
        }

        async function cancelAppointment(id) {
            const result = await Swal.fire({
                title: 'Cancel Appointment?',
                text: 'Are you sure you want to cancel this appointment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No, keep it'
            });

            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                try {
                    const response = await fetch(`/doctor/appointments/${id}/cancel`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Cancelled!', 'Appointment cancelled successfully.', 'success');
                        loadAppointments();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            }
        }
    </script>
@endpush