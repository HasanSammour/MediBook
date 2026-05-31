{{-- resources/views/hospital/payments/index.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Payments Management')
@section('page-title', 'Payments Management')
@section('page-subtitle', 'Track and manage all patient payments')

@push('styles')
    <style>
        /* ============================================
                           SUMMARY CARDS
                           ============================================ */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summary-info h4 {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .summary-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Loading Skeleton */
        .card-skeleton {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .skeleton-info {
            flex: 1;
        }

        .skeleton-line-sm {
            height: 10px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            margin-bottom: 8px;
            width: 60%;
            animation: shimmer 1.5s infinite;
        }

        .skeleton-line-lg {
            height: 24px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            width: 70%;
            animation: shimmer 1.5s infinite;
        }

        .skeleton-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 12px;
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

        /* ============================================
                           FILTER BAR
                           ============================================ */
        .filters-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filter-row-1 {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-row-2 {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            justify-content: space-between;
        }

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

        .filter-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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

        .filter-search input:focus,
        .filter-select select:focus,
        .date-item input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

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
                           PAYMENTS TABLE
                           ============================================ */
        .payments-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            -webkit-overflow-scrolling: touch;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .payments-table th,
        .payments-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .payments-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .receipt-id {
            font-weight: 700;
            color: var(--primary-color);
            white-space: nowrap;
        }

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

        .amount {
            font-weight: 700;
            color: var(--secondary-color);
            white-space: nowrap;
        }

        .method-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .method-cash {
            background: #d1fae5;
            color: #065f46;
        }

        .method-card {
            background: #dbeafe;
            color: #1e40af;
        }

        .method-insurance {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-datetime {
            white-space: nowrap;
        }

        .payment-date {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .payment-time {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

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

        /* ============================================
                           RESPONSIVE
                           ============================================ */
        @media (max-width: 1024px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

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
            .summary-cards {
                grid-template-columns: 1fr;
            }

            .payments-table th,
            .payments-table td {
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
    <!-- Summary Cards with Loading Skeleton -->
    <div class="summary-cards" id="summaryCards">
        <div class="card-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="card-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="card-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
        <div class="card-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon"></div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filters-card">
        <div class="filter-row-1">
            <div class="filter-search">
                <div class="filter-label">Search</div>
                <input type="text" id="searchInput" placeholder="Patient name or receipt ID...">
            </div>
            <div class="filter-select">
                <div class="filter-label">Payment Method</div>
                <select id="methodFilter">
                    <option value="all">All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="insurance">Insurance</option>
                </select>
            </div>
            <div class="filter-select">
                <div class="filter-label">Doctor</div>
                <select id="doctorFilter">
                    <option value="all">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
                <button class="btn-export" onclick="exportPayments()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="payments-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading payments...</div>
        </div>
        <div id="tableContent">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Receipt ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Date & Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="paymentsTableBody"></tbody>
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
        let currentMethod = 'all';
        let currentDoctor = 'all';
        let currentDateFrom = '';
        let currentDateTo = '';
        let searchTimeout;

        async function loadPayments() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');

            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            const tbody = document.getElementById('paymentsTableBody');
            tbody.innerHTML = Array(5).fill(0).map(() => `
                            <tr>
                                <td colspan="7">
                                    <div style="display: flex; gap: 1rem; padding: 0.5rem;">
                                        <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 2; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 2; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 0.8; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                        <div style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    </div>
                                </td>
                            </tr>
                        `).join('');

            try {
                const url = new URL('{{ route("hospital.payments.data") }}');
                url.searchParams.set('page', currentPage);
                if (currentSearch) url.searchParams.set('search', currentSearch);
                if (currentMethod !== 'all') url.searchParams.set('method', currentMethod);
                if (currentDoctor !== 'all') url.searchParams.set('doctor_id', currentDoctor);
                if (currentDateFrom) url.searchParams.set('date_from', currentDateFrom);
                if (currentDateTo) url.searchParams.set('date_to', currentDateTo);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    updateSummaryCards(data.summary);
                    renderPaymentsTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading payments:', error);
                showEmptyState();
            } finally {
                loadingContainer.style.display = 'none';
                tableContent.style.display = 'block';
            }
        }

        function updateSummaryCards(summary) {
            const container = document.getElementById('summaryCards');
            container.innerHTML = `
                            <div class="summary-card">
                                <div class="summary-info">
                                    <h4>Total Revenue</h4>
                                    <div class="summary-amount">$${summary.total_revenue.toLocaleString()}</div>
                                </div>
                                <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-info">
                                    <h4>Total Transactions</h4>
                                    <div class="summary-amount">${summary.total_count}</div>
                                </div>
                                <div class="summary-icon"><i class="fas fa-receipt"></i></div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-info">
                                    <h4>Cash Payments</h4>
                                    <div class="summary-amount">$${summary.cash_total.toLocaleString()}</div>
                                </div>
                                <div class="summary-icon"><i class="fas fa-money-bill"></i></div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-info">
                                    <h4>Card/Insurance</h4>
                                    <div class="summary-amount">$${(summary.card_total + summary.insurance_total).toLocaleString()}</div>
                                </div>
                                <div class="summary-icon"><i class="fas fa-credit-card"></i></div>
                            </div>
                        `;
        }

        function renderPaymentsTable(payments) {
            const tbody = document.getElementById('paymentsTableBody');

            if (!payments || payments.length === 0) {
                tbody.innerHTML = `
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-receipt"></i>
                                            <h3>No payments found</h3>
                                            <p>Try adjusting your search or filter criteria</p>
                                            <button onclick="resetFilters()" class="btn btn-outline btn-sm">Reset Filters</button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                return;
            }

            tbody.innerHTML = payments.map(payment => `
                            <tr>
                                <td class="receipt-id">${escapeHtml(payment.receipt_no)}</td>
                                <td>
                                    <div class="patient-cell">
                                        <div class="patient-avatar">
                                            ${payment.patient_avatar_html || `<span style="color: white; font-weight: 600;">${escapeHtml(payment.patient_name.charAt(0))}</span>`}
                                        </div>
                                        <div class="patient-info">
                                            <div class="patient-name">${escapeHtml(payment.patient_name)}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="doctor-cell">
                                        <div class="doctor-avatar">
                                            ${payment.doctor_avatar_html || `<span style="color: white; font-weight: 600;">${escapeHtml(payment.doctor_name.charAt(0))}</span>`}
                                        </div>
                                        <div class="doctor-info">
                                            <div class="doctor-name">${escapeHtml(payment.doctor_name)}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="amount">${payment.formatted_amount}</td>
                                <td><span class="method-badge method-${payment.payment_method.toLowerCase()}">${payment.payment_method}</span></td>
                                <td class="payment-datetime">
                                    <div class="payment-date">${escapeHtml(payment.payment_date)}</div>
                                    <div class="payment-time">${escapeHtml(payment.payment_time)}</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view" onclick="viewPaymentDetails(${payment.id})" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `).join('');
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
            loadPayments();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('methodFilter').value = 'all';
            document.getElementById('doctorFilter').value = 'all';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';

            currentSearch = '';
            currentMethod = 'all';
            currentDoctor = 'all';
            currentDateFrom = '';
            currentDateTo = '';
            currentPage = 1;

            loadPayments();
        }

        async function viewPaymentDetails(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(`/hospital/payments/${id}/details`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    const p = data.payment;

                    Swal.fire({
                        title: 'Payment Receipt',
                        html: `
                        <div style="text-align: center;">
                            <!-- Receipt ID -->
                            <div style="background: #eff6ff; display: inline-block; padding: 6px 16px; border-radius: 30px; margin-bottom: 20px;">
                                <span style="font-size: 0.9rem; font-weight: 700; color: #2563eb; letter-spacing: 0.5px;">${p.receipt_no}</span>
                            </div>

                            <!-- Payment Details -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-credit-card" style="color: #2563eb; margin-right: 6px;"></i> Payment Details
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.8rem;">
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Amount</div>
                                        <div style="font-weight: 700; color: #10b981;">${p.amount}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Method</div>
                                        <div><span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 500; background: ${p.payment_method === 'Cash' ? '#d1fae5' : (p.payment_method === 'Card' ? '#dbeafe' : '#fef3c7')}; color: ${p.payment_method === 'Cash' ? '#065f46' : (p.payment_method === 'Card' ? '#1e40af' : '#92400e')};">${p.payment_method}</span></div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Date</div>
                                        <div style="font-weight: 500;">${p.payment_date}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Time</div>
                                        <div style="font-weight: 500;">${p.payment_time}</div>
                                    </div>
                                </div>
                                ${p.notes ? `
                                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                    <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 4px;">Notes</div>
                                    <div style="font-size: 0.75rem; background: #fef3c7; padding: 8px; border-radius: 8px; color: #92400e;">${escapeHtml(p.notes)}</div>
                                </div>
                                ` : ''}
                            </div>

                            <!-- Patient Information -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-user" style="color: #2563eb; margin-right: 6px;"></i> Patient Information
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.8rem;">
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Name</div>
                                        <div style="font-weight: 500;">${escapeHtml(p.patient.name)}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Phone</div>
                                        <div style="font-weight: 500;">${escapeHtml(p.patient.phone)}</div>
                                    </div>
                                    <div style="grid-column: span 2;">
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Email</div>
                                        <div style="font-weight: 500; word-break: break-all;">${escapeHtml(p.patient.email)}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor Information -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-user-md" style="color: #2563eb; margin-right: 6px;"></i> Doctor Information
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.8rem;">
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Name</div>
                                        <div style="font-weight: 500;">${escapeHtml(p.doctor.name)}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Specialty</div>
                                        <div style="font-weight: 500;">${escapeHtml(p.doctor.specialty)}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Information -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-calendar-alt" style="color: #2563eb; margin-right: 6px;"></i> Appointment Information
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.8rem;">
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Date</div>
                                        <div style="font-weight: 500;">${p.appointment.date}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Time</div>
                                        <div style="font-weight: 500;">${p.appointment.time}</div>
                                    </div>
                                    <div>
                                        <div style="color: #6b7280; font-size: 0.7rem; margin-bottom: 2px;">Status</div>
                                        <div><span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 500; background: #d1fae5; color: #065f46;">${p.appointment.status}</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hospital Information -->
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-hospital" style="color: #2563eb; margin-right: 6px;"></i> Hospital
                                </div>
                                <div style="font-size: 0.8rem; font-weight: 500;">${escapeHtml(p.hospital.name)}</div>
                                <div style="font-size: 0.7rem; color: #6b7280; margin-top: 4px;">${escapeHtml(p.hospital.address)} | ${escapeHtml(p.hospital.phone)}</div>
                            </div>

                            ${p.notes ? `
                            <div style="background: #f9fafb; border-radius: 12px; padding: 12px 14px; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.8rem; color: #1f2937; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb;">
                                    <i class="fas fa-sticky-note" style="color: #2563eb; margin-right: 6px;"></i> Additional Information
                                </div>
                                <div style="font-size: 0.8rem;">${escapeHtml(p.notes)}</div>
                            </div>
                            ` : ''}
                        </div>
                    `,
                        icon: 'success',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#2563eb',
                        width: '550px',
                        showCloseButton: true,
                        showConfirmButton: true
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Could not load payment details.', 'error');
            }
        }

        function exportPayments() {
            const params = new URLSearchParams();
            if (currentSearch) params.append('search', currentSearch);
            if (currentMethod !== 'all') params.append('method', currentMethod);
            if (currentDoctor !== 'all') params.append('doctor_id', currentDoctor);
            if (currentDateFrom) params.append('date_from', currentDateFrom);
            if (currentDateTo) params.append('date_to', currentDateTo);

            window.location.href = `{{ route("hospital.payments.export") }}?${params.toString()}`;
        }

        function debouncedSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = document.getElementById('searchInput').value;
                currentPage = 1;
                loadPayments();
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
            loadPayments();

            document.getElementById('searchInput').addEventListener('input', debouncedSearch);
            document.getElementById('methodFilter').addEventListener('change', function () {
                currentMethod = this.value;
                currentPage = 1;
                loadPayments();
            });
            document.getElementById('doctorFilter').addEventListener('change', function () {
                currentDoctor = this.value;
                currentPage = 1;
                loadPayments();
            });
            document.getElementById('dateFrom').addEventListener('change', function () {
                currentDateFrom = this.value;
                currentPage = 1;
                loadPayments();
            });
            document.getElementById('dateTo').addEventListener('change', function () {
                currentDateTo = this.value;
                currentPage = 1;
                loadPayments();
            });
        });
    </script>
@endpush