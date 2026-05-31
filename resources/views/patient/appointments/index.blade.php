@extends('layouts.patient')

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
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: flex-end;
        }

        .search-input {
            flex: 1;
            position: relative;
        }

        .search-input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .search-input input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            height: 46px;
        }

        .search-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .reset-btn {
            padding: 0 20px;
            height: 46px;
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .reset-btn:hover {
            background: #e5e7eb;
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
            min-width: 700px;
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

        .doctor-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doctor-avatar {
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

        .doctor-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .doctor-specialty {
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        .hospital-name {
            color: var(--dark-color);
        }

        .appointment-date {
            font-weight: 500;
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

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .fee-amount {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
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

        .action-btn.cancel {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-btn.cancel:hover {
            background: #fecaca;
        }

        .action-btn.disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 30px;
        }

        .loading-spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
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
            margin-top: 8px;
            color: var(--gray-color);
            font-size: 0.8rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.1rem;
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

        .pagination-container {
            display: flex;
            justify-content: flex-end;
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

            .search-bar {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .reset-btn {
                width: 100%;
                justify-content: center;
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
        <button class="filter-btn" data-status="completed">Completed</button>
        <button class="filter-btn" data-status="cancelled">Cancelled</button>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by doctor name, specialty, or hospital...">
        </div>
        <button class="reset-btn" id="resetBtn">
            <i class="fas fa-undo-alt"></i> Reset
        </button>
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
        <div class="pagination-info" id="paginationInfo">
            Showing 0 to 0 of 0 results
        </div>
        <div class="pagination-container" id="paginationContainer"></div>
    </div>
@endsection

@push('scripts')
    <script>
        // ESCAPE HTML FUNCTION (Prevent XSS attacks)
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        let currentStatus = 'all';
        let currentSearch = '';
        let currentPage = 1;
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function () {
            loadAppointments();

            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentStatus = this.getAttribute('data-status');
                    currentPage = 1;
                    loadAppointments();
                });
            });

            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadAppointments();
                }, 500);
            });

            // Reset button
            document.getElementById('resetBtn').addEventListener('click', function () {
                document.getElementById('searchInput').value = '';
                currentSearch = '';
                currentStatus = 'all';
                currentPage = 1;
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.filter-btn[data-status="all"]').classList.add('active');
                loadAppointments();
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
                params.append('search', currentSearch);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('patient.appointments.data') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                // DEBUG: Log the first appointment to see the structure
                if (data.data && data.data.length > 0) {
                    console.log('First appointment data:', data.data[0]);
                    console.log('Doctor data:', data.data[0].doctor);
                    console.log('Doctor avatar_html:', data.data[0].doctor?.avatar_html);
                }

                if (data.success) {
                    renderAppointmentsTable(data);
                    resultsInfo.style.display = 'flex';
                    paginationWrapper.style.display = 'flex';

                    document.getElementById('resultsFrom').innerText = data.from || 0;
                    document.getElementById('resultsTo').innerText = data.to || 0;
                    document.getElementById('resultsTotal').innerText = data.total;
                    document.getElementById('paginationInfo').innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;

                    renderPagination(data);
                }
            } catch (error) {
                console.error('Error loading appointments:', error);
                tableContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Error loading appointments</h3>
                    <p>Please try again later.</p>
                </div>
            `;
            }
        }

        function renderAppointmentsTable(data) {
            const tableContainer = document.getElementById('tableContainer');

            if (data.data.length === 0) {
                tableContainer.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No appointments found</h3>
                        <p>Try adjusting your search or filter criteria.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Hospital</th>
                            <th>Date & Time</th>
                            <th>Fee</th>
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
                const doctorName = apt.doctor?.name || 'Doctor';
                const doctorSpecialty = apt.doctor?.specialization || 'General';
                const doctorAvatar = apt.doctor?.avatar_html || `<div class="doctor-avatar" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2563eb, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">${escapeHtml(doctorName.charAt(0))}</div>`;
                const hospitalName = apt.hospital?.name || 'Hospital';
                const fee = apt.doctor?.consultation_fee || 0;
                const statusClass = getStatusClass(apt.status);
                const statusText = apt.status.charAt(0).toUpperCase() + apt.status.slice(1);
                const canCancel = (apt.status === 'pending' || apt.status === 'confirmed') && new Date(apt.appointment_date) > new Date();

                html += `
                    <tr>
                        <td>
                            <div class="doctor-cell">
                                <div class="doctor-avatar">${doctorAvatar}</div>
                                <div>
                                    <div class="doctor-name">${escapeHtml(doctorName)}</div>
                                    <div class="doctor-specialty">${escapeHtml(doctorSpecialty)}</div>
                                </div>
                            </div>
                         </td>
                        <td class="hospital-name">${escapeHtml(hospitalName)}</td>
                        <td>
                            <div class="appointment-date">${formattedDate}</div>
                            <div class="appointment-time">${formattedTime}</div>
                        </td>
                        <td class="fee-amount">$${fee}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view" onclick="viewAppointment(${apt.id})">View</button>
                                ${canCancel ? `<button class="action-btn cancel" onclick="cancelAppointment(${apt.id})">Cancel</button>` : '<button class="action-btn disabled" disabled>Cancelled</button>'}
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

        function getStatusClass(status) {
            switch (status) {
                case 'confirmed': return 'status-confirmed';
                case 'pending': return 'status-pending';
                case 'completed': return 'status-completed';
                case 'cancelled': return 'status-cancelled';
                default: return 'status-pending';
            }
        }

        async function viewAppointment(id) {
            try {
                const response = await fetch(`{{ url('patient/appointments') }}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const apt = data.appointment;
                    const date = new Date(apt.appointment_date);
                    const formattedDate = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                    const doctorName = apt.doctor?.name || 'Doctor';
                    const doctorSpecialty = apt.doctor?.specialization || 'General';
                    const hospitalName = apt.hospital?.name || 'Hospital';
                    const fee = apt.doctor?.consultation_fee || 0;
                    const statusText = apt.status.charAt(0).toUpperCase() + apt.status.slice(1);
                    const notes = apt.notes || 'No notes available';

                    Swal.fire({
                        title: 'Appointment Details',
                        html: `
                                        <div style="text-align: left;">
                                            <p><strong>📅 Date:</strong> ${formattedDate}</p>
                                            <p><strong>⏰ Time:</strong> ${formattedTime}</p>
                                            <p><strong>👨‍⚕️ Doctor:</strong> ${doctorName} (${doctorSpecialty})</p>
                                            <p><strong>🏥 Hospital:</strong> ${hospitalName}</p>
                                            <p><strong>💰 Fee:</strong> $${fee}</p>
                                            <p><strong>📌 Status:</strong> <span style="color: ${apt.status === 'completed' ? '#10b981' : apt.status === 'cancelled' ? '#ef4444' : '#f59e0b'}">${statusText}</span></p>
                                            <hr>
                                            <p><strong>📝 Doctor's Notes:</strong></p>
                                            <p style="background: #f9fafb; padding: 10px; border-radius: 8px;">${notes}</p>
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

        async function cancelAppointment(id) {
            const appointment = await getAppointmentDetails(id);
            const appointmentDate = new Date(appointment.appointment_date);
            const hoursUntil = Math.floor((appointmentDate - new Date()) / (1000 * 60 * 60));

            if (hoursUntil < 24) {
                Swal.fire({
                    title: 'Cannot Cancel',
                    html: `You cannot cancel this appointment because it's within <strong>${hoursUntil} hours</strong> of the scheduled time.<br><br>Cancellation is only allowed up to 24 hours before.`,
                    icon: 'warning',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            Swal.fire({
                title: 'Cancel Appointment?',
                text: `Are you sure you want to cancel your appointment with ${appointment.doctor?.name || 'Doctor'}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    try {
                        const response = await fetch(`{{ url('patient/appointments') }}/${id}/cancel`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Cancelled!', 'Your appointment has been cancelled.', 'success');
                            loadAppointments();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                }
            });
        }

        async function getAppointmentDetails(id) {
            const response = await fetch(`{{ url('patient/appointments') }}/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            return data.appointment;
        }
    </script>
@endpush