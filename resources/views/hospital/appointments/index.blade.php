{{-- resources/views/hospital/appointments/index.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Hospital Appointments')
@section('page-title', 'Appointments Management')
@section('page-subtitle', 'View and manage all appointments in your hospital')

@push('styles')
    <style>
        /* ============================================
                                               FILTER BAR - COMPLETELY REWRITTEN
                                               ============================================ */
        .filters-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Row 1: Search + Doctor + Status */
        .filter-row-1 {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        /* Row 2: Date Range + Buttons */
        .filter-row-2 {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            justify-content: space-between;
        }

        /* Individual filter items */
        .filter-search {
            flex: 2;
            min-width: 220px;
        }

        .filter-select {
            flex: 1;
            min-width: 150px;
        }

        .filter-date {
            display: flex;
            gap: 0.75rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .date-item {
            min-width: 140px;
        }

        .filter-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* Labels */
        .filter-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Inputs and Selects */
        .filter-search input,
        .filter-select select,
        .date-item input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .filter-search input {
            padding-left: 36px;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%236b7280" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>');
            background-repeat: no-repeat;
            background-position: 12px center;
            background-size: 18px;
        }

        .filter-search input:focus,
        .filter-select select:focus,
        .date-item input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Buttons */
        .btn-reset {
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .btn-export {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        /* ============================================
                                               APPOINTMENTS TABLE
                                               ============================================ */
        .appointments-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            -webkit-overflow-scrolling: touch;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .appointments-table th,
        .appointments-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .appointments-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        /* Patient Cell */
        .patient-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 160px;
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

        .patient-info .patient-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 2px;
        }

        /* Doctor Cell */
        .doctor-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 160px;
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-info .doctor-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 2px;
        }

        /* Date/Time */
        .appointment-datetime {
            white-space: nowrap;
        }

        .appointment-date {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .appointment-time {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Payment Badge */
        .payment-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            background: #f3f4f6;
            color: var(--dark-color);
            white-space: nowrap;
        }

        .payment-paid {
            background: #d1fae5;
            color: #065f46;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-btn {
            background: #f3f4f6;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .action-btn.view {
            color: var(--primary-color);
        }

        .action-btn.view:hover {
            background: rgba(37, 99, 235, 0.15);
        }

        .action-btn.payment {
            color: #10b981;
        }

        .action-btn.payment:hover {
            background: rgba(16, 185, 129, 0.15);
        }

        /* Loading */
        .loading-container {
            text-align: center;
            padding: 40px;
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

        .payment-notes {
            background: #fef3c7;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            color: #92400e;
            margin-top: 6px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            display: block;
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
            justify-content: flex-end;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .page-link {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--dark-color);
            font-size: 0.8rem;
            padding: 0 10px;
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Payment Record Modal Styles */
        .payment-record-modal .swal2-title {
            font-size: 1.1rem;
            font-weight: 700;
            padding-top: 0.5rem;
        }

        .payment-record-modal .swal2-html-container {
            margin: 0.5rem 0 0 !important;
            padding: 0 !important;
        }

        .payment-record-modal .swal2-actions {
            margin-top: 1rem !important;
        }

        .payment-record-modal .swal2-confirm {
            background: #10b981 !important;
            border-radius: 40px !important;
            padding: 8px 20px !important;
            font-size: 0.85rem !important;
        }

        .payment-record-modal .swal2-confirm i {
            margin-right: 6px;
        }

        .payment-record-modal .swal2-cancel {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
            border-radius: 40px !important;
            padding: 8px 20px !important;
            font-size: 0.85rem !important;
        }

        /* ============================================
                                               RESPONSIVE
                                               ============================================ */
        @media (max-width: 900px) {
            .filter-row-1 {
                flex-direction: column;
            }

            .filter-search,
            .filter-select {
                width: 100%;
            }

            .filter-row-2 {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-date {
                flex-direction: column;
                width: 100%;
            }

            .date-item {
                width: 100%;
            }

            .filter-actions {
                justify-content: stretch;
            }

            .btn-reset,
            .btn-export {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {

            .appointments-table th,
            .appointments-table td {
                padding: 0.75rem;
            }

            .patient-cell,
            .doctor-cell {
                min-width: 140px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filter Bar -->
    <div class="filters-card">
        <!-- Row 1: Search, Doctor, Status -->
        <div class="filter-row-1">
            <div class="filter-search">
                <div class="filter-label">Search Patient</div>
                <input type="text" id="searchInput" placeholder="Search by patient name...">
            </div>
            <div class="filter-select">
                <div class="filter-label">Filter by Doctor</div>
                <select id="doctorFilter">
                    <option value="all">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-select">
                <div class="filter-label">Filter by Status</div>
                <select id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Row 2: Date Range + Buttons -->
        <div class="filter-row-2">
            <div class="filter-date">
                <div class="date-item">
                    <div class="filter-label">From Date</div>
                    <input type="date" id="dateFrom">
                </div>
                <div class="date-item">
                    <div class="filter-label">To Date</div>
                    <input type="date" id="dateTo">
                </div>
            </div>
            <div class="filter-actions">
                <button class="btn-reset" onclick="resetFilters()">
                    <i class="fas fa-undo-alt"></i> Reset Filters
                </button>
                <button class="btn-export" onclick="exportAppointments()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="appointments-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading appointments...</div>
        </div>
        <div id="tableContent">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody">
                    <!-- Skeleton rows will be shown here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper" id="paginationWrapper"></div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let currentSearch = '';
        let currentDoctor = 'all';
        let currentStatus = 'all';
        let currentDateFrom = '';
        let currentDateTo = '';
        let searchTimeout;

        async function loadAppointments() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');

            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            // Show skeleton rows
            const tbody = document.getElementById('appointmentsTableBody');
            tbody.innerHTML = Array(5).fill(0).map(() => `
                                                <tr>
                                                    <td colspan="6">
                                                        <div style="display: flex; gap: 1rem; padding: 0.5rem;">
                                                            <div style="height: 20px; flex: 2; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                            <div style="height: 20px; flex: 2; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                            <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                            <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                            <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                            <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `).join('');

            try {
                const url = new URL('{{ route("hospital.appointments.data") }}');
                url.searchParams.set('page', currentPage);
                if (currentSearch) url.searchParams.set('search', currentSearch);
                if (currentDoctor !== 'all') url.searchParams.set('doctor_id', currentDoctor);
                if (currentStatus !== 'all') url.searchParams.set('status', currentStatus);
                if (currentDateFrom) url.searchParams.set('date_from', currentDateFrom);
                if (currentDateTo) url.searchParams.set('date_to', currentDateTo);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    renderAppointmentsTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading appointments:', error);
                showEmptyState();
            } finally {
                loadingContainer.style.display = 'none';
                tableContent.style.display = 'block';
            }
        }

        function renderAppointmentsTable(appointments) {
            const tbody = document.getElementById('appointmentsTableBody');

            if (!appointments || appointments.length === 0) {
                tbody.innerHTML = `
                                            <tr>
                                                <td colspan="6">
                                                    <div class="empty-state">
                                                        <i class="fas fa-calendar-times"></i>
                                                        <h3>No appointments found</h3>
                                                        <p>Try adjusting your search or filter criteria</p>
                                                        <button onclick="resetFilters()" class="btn btn-outline btn-sm">Reset Filters</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                return;
            }

            tbody.innerHTML = appointments.map(apt => {
                let statusClass = '';
                switch (apt.status) {
                    case 'pending': statusClass = 'status-pending'; break;
                    case 'confirmed': statusClass = 'status-confirmed'; break;
                    case 'completed': statusClass = 'status-completed'; break;
                    case 'cancelled': statusClass = 'status-cancelled'; break;
                }

                const paymentClass = apt.has_payment ? 'payment-paid' : '';
                const paymentText = apt.has_payment ? `Paid ${apt.payment_amount}` : 'Not Paid';

                // Check if appointment is completed AND has no payment
                const showPaymentButton = apt.status === 'completed' && !apt.has_payment;

                return `
                                            <tr>
                                                <td>
                                                    <div class="patient-cell">
                                                        <div class="patient-avatar">
                                                            ${apt.patient_avatar_html || `<span style="color: white; font-weight: 600;">${escapeHtml(apt.patient_name.charAt(0))}</span>`}
                                                        </div>
                                                        <div class="patient-info">
                                                            <div class="patient-name">${escapeHtml(apt.patient_name)}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="doctor-cell">
                                                        <div class="doctor-avatar">
                                                            ${apt.doctor_avatar_html || `<span style="color: white; font-weight: 600;">${escapeHtml(apt.doctor_name.charAt(0))}</span>`}
                                                        </div>
                                                        <div class="doctor-info">
                                                            <div class="doctor-name">${escapeHtml(apt.doctor_name)}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="appointment-datetime">
                                                    <div class="appointment-date">${escapeHtml(apt.appointment_date)}</div>
                                                    <div class="appointment-time">${escapeHtml(apt.appointment_time)}</div>
                                                </td>
                                                <td><span class="status-badge ${statusClass}">${escapeHtml(apt.status_text)}</span></td>
                                                <td><span class="payment-badge ${paymentClass}">${paymentText}</span></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="action-btn view" onclick="viewAppointmentDetails(${apt.id})" title="View Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        ${showPaymentButton ? `
                                                            <button class="action-btn payment" onclick="recordPayment(${apt.id})" title="Record Payment">
                                                                <i class="fas fa-receipt"></i> Payment
                                                            </button>
                                                        ` : ''}
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
            }).join('');
        }

        function renderPagination(data) {
            const wrapper = document.getElementById('paginationWrapper');

            if (data.last_page <= 1) {
                wrapper.innerHTML = '';
                return;
            }

            let html = '<div class="pagination">';

            html += `<button class="page-link ${data.current_page === 1 ? 'disabled' : ''}" 
                                                        onclick="${data.current_page > 1 ? `goToPage(${data.current_page - 1})` : ''}" 
                                                        ${data.current_page === 1 ? 'disabled' : ''}>
                                                        &laquo;
                                                    </button>`;

            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    html += `<button class="page-link ${i === data.current_page ? 'active' : ''}" 
                                                                onclick="goToPage(${i})">${i}</button>`;
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    html += '<span class="page-link disabled">...</span>';
                }
            }

            html += `<button class="page-link ${data.current_page === data.last_page ? 'disabled' : ''}" 
                                                        onclick="${data.current_page < data.last_page ? `goToPage(${data.current_page + 1})` : ''}" 
                                                        ${data.current_page === data.last_page ? 'disabled' : ''}>
                                                        &raquo;
                                                    </button>`;

            html += '</div>';
            wrapper.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadAppointments();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('doctorFilter').value = 'all';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';

            currentSearch = '';
            currentDoctor = 'all';
            currentStatus = 'all';
            currentDateFrom = '';
            currentDateTo = '';
            currentPage = 1;

            loadAppointments();
        }

        async function viewAppointmentDetails(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(`/hospital/appointments/${id}/details`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    const apt = data.appointment;

                    let statusBadgeClass = '';
                    switch (apt.status) {
                        case 'pending': statusBadgeClass = 'status-pending'; break;
                        case 'confirmed': statusBadgeClass = 'status-confirmed'; break;
                        case 'completed': statusBadgeClass = 'status-completed'; break;
                        case 'cancelled': statusBadgeClass = 'status-cancelled'; break;
                    }

                    let paymentHtml = '';
                    if (apt.payment) {
                        // Check if payment has notes
                        const hasPaymentNotes = apt.payment.notes && apt.payment.notes.trim() !== '';
                        
                        paymentHtml = `
                            <div style="background: #d1fae5; padding: 12px; border-radius: 12px; margin-top: 1rem;">
                                <div style="font-weight: 600; margin-bottom: 8px; font-size: 0.8rem;"><i class="fas fa-receipt"></i> Payment Details</div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.75rem;">
                                    <div><span style="color: #6b7280;">Amount:</span> <strong style="color: #10b981;">${apt.payment.amount}</strong></div>
                                    <div><span style="color: #6b7280;">Method:</span> <span style="display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; background: #dbeafe; color: #1e40af;">${apt.payment.method}</span></div>
                                    <div colspan="2"><span style="color: #6b7280;">Date:</span> ${apt.payment.date}</div>
                                </div>
                                ${hasPaymentNotes ? `
                                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid rgba(0,0,0,0.08);">
                                    <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 4px;"><i class="fas fa-sticky-note"></i> Payment Notes:</div>
                                    <div style="font-size: 0.75rem; background: #fef3c7; padding: 8px 10px; border-radius: 8px; color: #92400e;">${escapeHtml(apt.payment.notes)}</div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                    } else if (apt.is_completed) {
                        paymentHtml = `
                            <div style="background: #fef3c7; padding: 12px; border-radius: 12px; margin-top: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                                    <span>Payment not recorded yet</span>
                                </div>
                                <button class="btn btn-primary btn-sm" style="padding: 6px 16px; font-size: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;" onclick="recordPayment(${apt.id}); Swal.close();">
                                    <i class="fas fa-receipt"></i> Record Payment
                                </button>
                            </div>
                        `;
                    }

                    Swal.fire({
                        title: `
                                                            <div style="display: flex; align-items: center; gap: 15px; justify-content: center; margin-bottom: 10px;">
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #2563eb, #1d4ed8); display: flex; align-items: center; justify-content: center;">
                                                                        ${apt.patient.avatar_html || `<span style="color: white; font-size: 18px; font-weight: 600;">${apt.patient.name.charAt(0)}</span>`}
                                                                    </div>
                                                                    <div style="text-align: left;">
                                                                        <div style="font-size: 0.9rem; font-weight: 700;">${escapeHtml(apt.patient.name)}</div>
                                                                        <div style="font-size: 0.65rem; color: #6b7280;">Patient</div>
                                                                    </div>
                                                                </div>
                                                                <i class="fas fa-arrow-right" style="color: #9ca3af; font-size: 0.8rem;"></i>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                                                        ${apt.doctor.avatar_html || `<span style="color: white; font-size: 18px; font-weight: 600;">${apt.doctor.name.charAt(0)}</span>`}
                                                                    </div>
                                                                    <div style="text-align: left;">
                                                                        <div style="font-size: 0.9rem; font-weight: 700;">${escapeHtml(apt.doctor.name)}</div>
                                                                        <div style="font-size: 0.65rem; color: #6b7280;">${escapeHtml(apt.doctor.specialty)}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `,
                        html: `
                                                            <div style="text-align: left; margin-top: 0.5rem;">
                                                                <div style="background: #f9fafb; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 8px; font-size: 0.8rem;"><i class="fas fa-calendar-alt"></i> Appointment Details</div>
                                                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.75rem;">
                                                                        <div><span style="color: #6b7280;">Date:</span> ${escapeHtml(apt.appointment_date)}</div>
                                                                        <div><span style="color: #6b7280;">Time:</span> ${escapeHtml(apt.appointment_time)}</div>
                                                                        <div><span style="color: #6b7280;">Fee:</span> ${escapeHtml(apt.doctor.formatted_fee)}</div>
                                                                        <div><span style="color: #6b7280;">Status:</span> <span class="status-badge ${statusBadgeClass}" style="display: inline-block; font-size: 0.7rem;">${escapeHtml(apt.status_text)}</span></div>
                                                                    </div>
                                                                </div>

                                                                <div style="background: #f9fafb; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 8px; font-size: 0.8rem;"><i class="fas fa-user"></i> Patient Information</div>
                                                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.75rem;">
                                                                        <div><span style="color: #6b7280;">Phone:</span> ${escapeHtml(apt.patient.phone)}</div>
                                                                        <div><span style="color: #6b7280;">Email:</span> ${escapeHtml(apt.patient.email)}</div>
                                                                        <div><span style="color: #6b7280;">Gender:</span> ${escapeHtml(apt.patient.gender)}</div>
                                                                        <div><span style="color: #6b7280;">Age:</span> ${apt.patient.age} years</div>
                                                                    </div>
                                                                </div>

                                                                <div style="background: #f9fafb; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 8px; font-size: 0.8rem;"><i class="fas fa-hospital"></i> Hospital</div>
                                                                    <div style="font-size: 0.75rem;">${escapeHtml(apt.hospital.name)}</div>
                                                                    <div style="font-size: 0.7rem; color: #6b7280;">${escapeHtml(apt.hospital.address)} | ${escapeHtml(apt.hospital.phone)}</div>
                                                                </div>

                                                                ${apt.patient_notes && apt.patient_notes !== 'No notes provided' ? `
                                                                <div style="background: #f0f9ff; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 4px; font-size: 0.75rem;"><i class="fas fa-sticky-note"></i> Patient Notes</div>
                                                                    <div style="font-size: 0.75rem;">${escapeHtml(apt.patient_notes)}</div>
                                                                </div>
                                                                ` : ''}

                                                                ${apt.doctor_notes && apt.doctor_notes !== 'No medical notes added yet' ? `
                                                                <div style="background: #f0fdf4; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 4px; font-size: 0.75rem;"><i class="fas fa-prescription-bottle"></i> Doctor's Notes</div>
                                                                    <div style="font-size: 0.75rem;">${escapeHtml(apt.doctor_notes)}</div>
                                                                </div>
                                                                ` : ''}

                                                                ${apt.cancellation_reason ? `
                                                                <div style="background: #fef2f2; padding: 12px; border-radius: 12px; margin-bottom: 0.75rem;">
                                                                    <div style="font-weight: 600; margin-bottom: 4px; font-size: 0.75rem;"><i class="fas fa-ban"></i> Cancellation Reason</div>
                                                                    <div style="font-size: 0.75rem;">${escapeHtml(apt.cancellation_reason)}</div>
                                                                </div>
                                                                ` : ''}

                                                                ${paymentHtml}
                                                            </div>
                                                        `,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Close',
                        width: '550px',
                        showCloseButton: true,
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Could not load appointment details.', 'error');
            }
        }

        function recordPayment(appointmentId) {
            // First, get appointment details to show fee
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/hospital/appointments/${appointmentId}/details`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const apt = data.appointment;
                        const doctorFee = apt.doctor.fee || 0;

                        Swal.close();

                        Swal.fire({
                            title: '<div style="font-size: 1.2rem; font-weight: 700;">Record Payment</div>',
                            html: `
                        <div style="text-align: left; margin-top: 0.5rem;">
                            <!-- Appointment Summary -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user-md" style="color: white; font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">${escapeHtml(apt.doctor.name)}</div>
                                        <div style="font-size: 0.7rem; color: #6b7280;">${escapeHtml(apt.doctor.specialty)}</div>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.75rem;">
                                    <div><span style="color: #6b7280;">Patient:</span> ${escapeHtml(apt.patient.name)}</div>
                                    <div><span style="color: #6b7280;">Date:</span> ${apt.appointment_date}</div>
                                    <div><span style="color: #6b7280;">Time:</span> ${apt.appointment_time}</div>
                                    <div><span style="color: #6b7280;">Consultation Fee:</span> <strong style="color: #10b981;">${apt.doctor.formatted_fee || '$' + doctorFee}</strong></div>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px;">
                                <div style="font-weight: 600; font-size: 0.8rem; margin-bottom: 12px; color: #1f2937;">
                                    <i class="fas fa-credit-card" style="color: #2563eb; margin-right: 6px;"></i> Payment Information
                                </div>

                                <!-- Payment Method -->
                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; font-weight: 600; font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;">Payment Method</label>
                                    <select id="payment_method" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.85rem; background: white; cursor: pointer;">
                                        <option value="cash">💵 Cash</option>
                                        <option value="card">💳 Credit/Debit Card</option>
                                        <option value="insurance">📄 Insurance</option>
                                    </select>
                                </div>

                                <!-- Amount with note about difference -->
                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; font-weight: 600; font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;">
                                        Amount ($) 
                                        <span style="font-weight: normal; color: #6b7280;">(Doctor fee: ${apt.doctor.formatted_fee || '$' + doctorFee})</span>
                                    </label>
                                    <input type="number" id="payment_amount" value="${doctorFee}" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.85rem;">
                                    <div id="amount_note_hint" style="font-size: 0.65rem; margin-top: 4px; display: none;">
                                        <i class="fas fa-info-circle"></i> <span id="amount_note_text"></span>
                                    </div>
                                </div>

                                <!-- Notes for difference -->
                                <div id="notes_section" style="display: none;">
                                    <label style="display: block; font-weight: 600; font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;">
                                        Reason for amount difference <span style="color: #ef4444;">*</span>
                                    </label>
                                    <textarea id="payment_notes" rows="2" placeholder="Example: Discount applied, Additional services, Insurance adjustment, etc..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.8rem; resize: vertical;"></textarea>
                                </div>
                            </div>
                        </div>
                    `,
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            confirmButtonText: '<i class="fas fa-save"></i> Record Payment',
                            cancelButtonText: 'Cancel',
                            cancelButtonColor: '#6b7280',
                            width: '550px',
                            padding: '1.2rem',
                            didOpen: () => {
                                // Add amount change listener to show notes section if amount differs from doctor fee
                                const amountInput = document.getElementById('payment_amount');
                                const notesSection = document.getElementById('notes_section');
                                const amountNoteHint = document.getElementById('amount_note_hint');
                                const amountNoteText = document.getElementById('amount_note_text');

                                if (amountInput) {
                                    const checkAmountDifference = () => {
    const enteredAmount = parseFloat(amountInput.value);
    const doctorFeeValue = parseFloat(doctorFee);
    
    console.log('Entered:', enteredAmount, 'Doctor Fee:', doctorFeeValue); // For debugging
    
    if (Math.abs(enteredAmount - doctorFeeValue) > 0.01) { // Allow small rounding differences
        notesSection.style.display = 'block';
        if (enteredAmount > doctorFeeValue) {
            amountNoteHint.style.display = 'block';
            amountNoteText.innerHTML = '⚠️ Amount is higher than doctor fee. Please explain why in the notes below.';
            amountNoteHint.style.color = '#f59e0b';
        } else if (enteredAmount < doctorFeeValue) {
            amountNoteHint.style.display = 'block';
            amountNoteText.innerHTML = '⚠️ Amount is lower than doctor fee. Please explain discount or adjustment in the notes below.';
            amountNoteHint.style.color = '#f59e0b';
        }
    } else {
        notesSection.style.display = 'none';
        amountNoteHint.style.display = 'none';
    }
};

                                    amountInput.addEventListener('input', checkAmountDifference);
                                    checkAmountDifference(); // Check initial value
                                }
                            },
                            preConfirm: () => {
                                const method = document.getElementById('payment_method').value;
                                const amount = document.getElementById('payment_amount').value;
                                const notes = document.getElementById('payment_notes')?.value || '';
                                const enteredAmount = parseFloat(amount);

                                if (!amount) {
                                    Swal.showValidationMessage('Please enter the amount');
                                    return false;
                                }
                                if (amount <= 0) {
                                    Swal.showValidationMessage('Amount must be greater than 0');
                                    return false;
                                }

                                // If amount is different from doctor fee, notes are required
                                if (enteredAmount !== doctorFee && !notes.trim()) {
                                    Swal.showValidationMessage('Please provide a reason for the amount difference in the notes field');
                                    return false;
                                }

                                return { method: method, amount: amount, notes: notes };
                            }
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                                try {
                                    const response = await fetch(`/hospital/payments/${appointmentId}/record`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            payment_method: result.value.method,
                                            amount: result.value.amount,
                                            notes: result.value.notes
                                        })
                                    });
                                    const data = await response.json();

                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Payment Recorded!',
                                            html: `
                                        <div style="text-align: center;">
                                            <div style="font-size: 0.9rem; color: #10b981; margin-bottom: 8px;">✓ Payment successful</div>
                                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px; text-align: left;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                    <span style="color: #6b7280;">Amount:</span>
                                                    <span style="font-weight: 700; color: #10b981;">$${result.value.amount}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                    <span style="color: #6b7280;">Method:</span>
                                                    <span>${result.value.method === 'cash' ? 'Cash' : (result.value.method === 'card' ? 'Card' : 'Insurance')}</span>
                                                </div>
                                                ${result.value.notes ? `
                                                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                                    <span style="color: #6b7280;">Notes:</span>
                                                    <div style="font-size: 0.75rem; margin-top: 4px;">${escapeHtml(result.value.notes)}</div>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                    `,
                                            icon: 'success',
                                            confirmButtonColor: '#2563eb',
                                            confirmButtonText: 'Close',
                                            timer: 2500
                                        });
                                        loadAppointments(); // Refresh the table
                                    } else {
                                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                                }
                            }
                        });
                    } else {
                        Swal.fire('Error', 'Could not load appointment details.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Could not load appointment details. Please try again.', 'error');
                });
        }

        function exportAppointments() {
            const params = new URLSearchParams();
            if (currentSearch) params.append('search', currentSearch);
            if (currentDoctor !== 'all') params.append('doctor_id', currentDoctor);
            if (currentStatus !== 'all') params.append('status', currentStatus);
            if (currentDateFrom) params.append('date_from', currentDateFrom);
            if (currentDateTo) params.append('date_to', currentDateTo);

            window.location.href = `{{ route("hospital.appointments.export") }}?${params.toString()}`;
        }

        function debouncedSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = document.getElementById('searchInput').value;
                currentPage = 1;
                loadAppointments();
            }, 300);
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

        document.addEventListener('DOMContentLoaded', function () {
            loadAppointments();

            document.getElementById('searchInput').addEventListener('input', debouncedSearch);
            document.getElementById('doctorFilter').addEventListener('change', function () {
                currentDoctor = this.value;
                currentPage = 1;
                loadAppointments();
            });
            document.getElementById('statusFilter').addEventListener('change', function () {
                currentStatus = this.value;
                currentPage = 1;
                loadAppointments();
            });
            document.getElementById('dateFrom').addEventListener('change', function () {
                currentDateFrom = this.value;
                currentPage = 1;
                loadAppointments();
            });
            document.getElementById('dateTo').addEventListener('change', function () {
                currentDateTo = this.value;
                currentPage = 1;
                loadAppointments();
            });
        });
    </script>
@endpush