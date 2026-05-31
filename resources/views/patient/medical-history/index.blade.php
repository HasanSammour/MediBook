@extends('layouts.patient')

@section('title', 'Medical History')

@section('page-title', 'Medical History')
@section('page-subtitle', 'View your past appointments and medical records')

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

        /* Filter Row - Horizontal Layout */
        .filter-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            background: var(--white);
            cursor: pointer;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-group {
            flex: 2;
            min-width: 200px;
        }

        .search-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .search-input {
            position: relative;
        }

        .search-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .search-input input {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .search-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .reset-btn {
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            height: 42px;
            margin-top: auto;
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

        /* Appointments List */
        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Appointment Card - Consistent Design */
        .appointment-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .appointment-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .appointment-header:hover {
            background: #f5f5f5;
        }

        /* Left Section - Doctor Info */
        .appointment-doctor {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 2;
        }

        .doctor-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doctor-icon i {
            font-size: 1.3rem;
            color: white;
        }

        .doctor-info {
            flex: 1;
        }

        .doctor-info h4 {
            font-size: 1rem;
            margin-bottom: 0.2rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .doctor-info p {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        /* Middle Section - Date & Time */
        .appointment-datetime {
            flex: 1;
            text-align: center;
        }

        .appointment-date {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .appointment-time {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        /* Right Section - Status & Expand */
        .appointment-status {
            flex: 0.5;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .expand-icon {
            color: var(--gray-color);
            transition: transform 0.3s ease;
        }

        .appointment-card.expanded .expand-icon {
            transform: rotate(180deg);
        }

        /* Appointment Details Body - Consistent Spacing */
        .appointment-body {
            display: none;
            padding: 1.5rem;
            border-top: 1px solid #f0f0f0;
        }

        .appointment-card.expanded .appointment-body {
            display: block;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-box {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }

        .detail-box label {
            font-size: 0.65rem;
            color: var(--gray-color);
            display: block;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-box .value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            word-break: break-word;
        }

        .medical-notes {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .medical-notes h4 {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .medical-notes p {
            font-size: 0.85rem;
            color: var(--dark-color);
            line-height: 1.5;
            margin-bottom: 0;
        }

        .patient-notes {
            background: #fef3c7;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .patient-notes h4 {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: #d97706;
            font-weight: 600;
        }

        .patient-notes p {
            font-size: 0.85rem;
            color: var(--dark-color);
            line-height: 1.5;
            margin-bottom: 0;
        }

        .payment-info {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .payment-info span {
            font-size: 0.8rem;
            color: var(--dark-color);
        }

        .payment-info strong {
            font-weight: 600;
        }

        .cancelled-reason {
            background: #fee2e2;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .cancelled-reason p {
            font-size: 0.8rem;
            color: #991b1b;
            margin-bottom: 0;
        }

        /* Loading Spinner - Same as Other Pages */
        .loading-container {
            text-align: center;
            padding: 40px;
            background: var(--white);
            border-radius: 20px;
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
            margin-top: 10px;
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: var(--white);
            border-radius: 20px;
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
            margin-top: 2rem;
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
        @media (max-width: 968px) {
            .filter-row {
                flex-direction: column;
            }

            .filter-group,
            .search-group {
                width: 100%;
            }

            .reset-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .appointment-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .appointment-doctor {
                flex-direction: column;
                width: 100%;
            }

            .appointment-datetime {
                width: 100%;
            }

            .appointment-status {
                width: 100%;
                justify-content: center;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .payment-info {
                flex-direction: column;
                text-align: center;
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
        <button class="filter-btn active" data-status="all">All History</button>
        <button class="filter-btn" data-status="completed">Completed</button>
        <button class="filter-btn" data-status="cancelled">Cancelled</button>
    </div>

    <!-- Filter Row - Horizontal Layout -->
    <div class="filter-row">
        <div class="filter-group">
            <label>Year</label>
            <select id="yearFilter">
                <option value="">All Years</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Month</label>
            <select id="monthFilter">
                <option value="">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="search-group">
            <label>Search Doctor</label>
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by doctor name or specialty...">
            </div>
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

    <!-- Appointments List -->
    <div id="appointmentsList">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading medical history...</div>
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
        let currentPage = 1;
        let currentStatus = 'all';
        let currentYear = '';
        let currentMonth = '';
        let currentSearch = '';
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function () {
            loadMedicalHistory();

            // Filter tabs
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentStatus = this.getAttribute('data-status');
                    currentPage = 1;
                    loadMedicalHistory();
                });
            });

            document.getElementById('yearFilter').addEventListener('change', function () {
                currentYear = this.value;
                currentPage = 1;
                loadMedicalHistory();
            });

            document.getElementById('monthFilter').addEventListener('change', function () {
                currentMonth = this.value;
                currentPage = 1;
                loadMedicalHistory();
            });

            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadMedicalHistory();
                }, 500);
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                document.getElementById('yearFilter').value = '';
                document.getElementById('monthFilter').value = '';
                document.getElementById('searchInput').value = '';
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.filter-btn[data-status="all"]').classList.add('active');
                currentYear = '';
                currentMonth = '';
                currentSearch = '';
                currentStatus = 'all';
                currentPage = 1;
                loadMedicalHistory();
            });
        });

        async function loadMedicalHistory() {
            const container = document.getElementById('appointmentsList');
            const resultsInfo = document.getElementById('resultsInfo');
            const paginationWrapper = document.getElementById('paginationWrapper');

            container.innerHTML = `<div class="loading-container"><div class="loading-spinner"></div><div class="loading-text">Loading medical history...</div></div>`;
            resultsInfo.style.display = 'none';
            paginationWrapper.style.display = 'none';

            try {
                const params = new URLSearchParams();
                params.append('status', currentStatus);
                if (currentYear) params.append('year', currentYear);
                if (currentMonth) params.append('month', currentMonth);
                if (currentSearch) params.append('search', currentSearch);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('patient.medical-history.data') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    // Populate year filter
                    if (data.years && data.years.length > 0 && document.getElementById('yearFilter').children.length === 1) {
                        const yearSelect = document.getElementById('yearFilter');
                        data.years.forEach(year => {
                            yearSelect.innerHTML += `<option value="${year}">${year}</option>`;
                        });
                    }

                    renderAppointments(data);
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
                container.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Error loading data</h3><p>Please try again later.</p></div>`;
            }
        }

        function renderAppointments(data) {
            const container = document.getElementById('appointmentsList');

            if (data.data.length === 0) {
                container.innerHTML = `<div class="empty-state"><i class="fas fa-notes-medical"></i><h3>No medical history found</h3><p>Your past appointments will appear here.</p></div>`;
                return;
            }

            let html = '<div class="appointments-list">';

            data.data.forEach(apt => {
                const date = new Date(apt.appointment_date);
                const formattedDate = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                const doctorName = apt.doctor?.name || 'Doctor';
                const doctorSpecialty = apt.doctor?.specialization || 'General Physician';
                const hospitalName = apt.hospital?.name || 'Hospital';
                const doctorNotes = apt.notes || (apt.status === 'cancelled' ? 'Appointment was cancelled.' : 'No medical notes available.');
                const patientNotes = apt.patient_notes || 'No notes provided.';
                const paymentAmount = apt.payment?.amount;
                const paymentMethod = apt.payment?.payment_method;
                const paymentDate = apt.payment?.payment_date ? new Date(apt.payment.payment_date).toLocaleDateString() : null;
                const consultationFee = apt.doctor?.consultation_fee || 0;
                const cancellationReason = apt.cancellation_reason || 'No reason provided.';
                const statusClass = apt.status === 'completed' ? 'status-completed' : 'status-cancelled';
                const statusText = apt.status === 'completed' ? 'Completed' : 'Cancelled';

                html += `
                <div class="appointment-card" data-id="${apt.id}">
                    <div class="appointment-header" onclick="toggleAppointment(${apt.id})">
                        <div class="appointment-doctor">
                            <div class="doctor-icon"><i class="fas fa-user-md"></i></div>
                            <div class="doctor-info">
                                <h4>${escapeHtml(doctorName)}</h4>
                                <p>${escapeHtml(doctorSpecialty)}</p>
                            </div>
                        </div>
                        <div class="appointment-datetime">
                            <div class="appointment-date">${formattedDate}</div>
                            <div class="appointment-time">${formattedTime}</div>
                        </div>
                        <div class="appointment-status">
                            <span class="status-badge ${statusClass}">${statusText}</span>
                            <div class="expand-icon"><i class="fas fa-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="appointment-body">
                        <div class="details-grid">
                            <div class="detail-box">
                                <label>Hospital</label>
                                <div class="value">${escapeHtml(hospitalName)}</div>
                            </div>
                            <div class="detail-box">
                                <label>Consultation Fee</label>
                                <div class="value">$${parseFloat(consultationFee).toFixed(2)}</div>
                            </div>
                        </div>

                        ${apt.status === 'cancelled' ? `
                        <div class="cancelled-reason">
                            <p><strong>Cancellation Reason:</strong> ${escapeHtml(cancellationReason)}</p>
                        </div>
                        ` : `
                        <div class="medical-notes">
                            <h4><i class="fas fa-stethoscope"></i> Doctor's Notes</h4>
                            <p>${escapeHtml(doctorNotes)}</p>
                        </div>
                        `}

                        <div class="patient-notes">
                            <h4><i class="fas fa-user"></i> Your Notes</h4>
                            <p>${escapeHtml(patientNotes)}</p>
                        </div>

                        ${paymentAmount && apt.status === 'completed' ? `
                        <div class="payment-info">
                            <span><strong>Payment:</strong> $${parseFloat(paymentAmount).toFixed(2)} (${paymentMethod})</span>
                            <span><strong>Paid on:</strong> ${paymentDate}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        function toggleAppointment(id) {
            const card = document.querySelector(`.appointment-card[data-id="${id}"]`);
            if (card) card.classList.toggle('expanded');
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
            loadMedicalHistory();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
@endpush