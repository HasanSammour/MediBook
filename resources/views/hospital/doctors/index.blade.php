{{-- resources/views/hospital/doctors/index.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Manage Doctors')
@section('page-title', 'Doctors Management')
@section('page-subtitle', 'Manage all doctors in your hospital')

@push('styles')
    <style>
        /* Stats Summary Cards */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-summary-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-summary-info h4 {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0.25rem;
        }
        
        .stat-summary-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-summary-icon {
            width: 40px;
            height: 40px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-summary-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Loading Skeleton for Stats */
        .stat-skeleton {
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
        
        .skeleton-icon-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-line-sm {
            height: 12px;
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

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Filter Bar */
        .filter-bar {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        
        .search-group {
            flex: 2;
            min-width: 200px;
            position: relative;
        }
        
        .search-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }
        
        .search-group input {
            width: 100%;
            padding: 10px 12px 10px 35px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        
        .filter-group {
            flex: 1;
            min-width: 150px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
        }
        
        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.8rem;
            background: white;
        }
        
        .action-buttons-bar {
            display: flex;
            gap: 0.5rem;
        }
        
        .reset-btn {
            background: #f3f4f6;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .reset-btn:hover {
            background: #e5e7eb;
        }
        
        .trash-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .trash-btn:hover {
            background: #fecaca;
        }
        
        .add-doctor-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .add-doctor-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Doctors Table Container */
        .doctors-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            -webkit-overflow-scrolling: touch;
        }
        
        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }
        
        .doctors-table th,
        .doctors-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .doctors-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            cursor: pointer;
            transition: background 0.3s ease;
            white-space: nowrap;
            font-size: 0.85rem;
        }
        
        .doctors-table th:hover {
            background: #f0f0f0;
        }
        
        .doctors-table th i {
            margin-left: 5px;
            font-size: 0.7rem;
        }
        
        /* Doctor Cell - Name and Email in separate lines */
        .doctor-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }
        
        .doctor-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
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
        
        .doctor-avatar span {
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .doctor-info {
            flex: 1;
        }
        
        .doctor-info .doctor-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .doctor-info .doctor-email {
            font-size: 0.7rem;
            color: var(--gray-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .specialty-cell {
            white-space: nowrap;
            font-size: 0.85rem;
        }
        
        .fee-value {
            font-weight: 700;
            color: var(--secondary-color);
            white-space: nowrap;
        }
        
        .patients-count {
            white-space: nowrap;
            font-size: 0.85rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .availability-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .availability-available {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .availability-unavailable {
            background: #fef3c7;
            color: #92400e;
        }
        
        /* Action Buttons - Horizontal */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        
        .action-btn {
            background: #f3f4f6;
            border: none;
            padding: 6px 10px;
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
        
        .action-btn.edit {
            color: var(--secondary-color);
        }
        
        .action-btn.edit:hover {
            background: rgba(16, 185, 129, 0.15);
        }
        
        .action-btn.toggle {
            color: #f59e0b;
        }
        
        .action-btn.toggle:hover {
            background: rgba(245, 158, 11, 0.15);
        }
        
        .action-btn.reset {
            color: #8b5cf6;
        }
        
        .action-btn.reset:hover {
            background: rgba(139, 92, 246, 0.15);
        }
        
        .action-btn.delete {
            color: #dc2626;
        }
        
        .action-btn.delete:hover {
            background: rgba(220, 38, 38, 0.15);
        }

        /* Loading Container */
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        
        /* Responsive */
        @media (max-width: 1024px) {
            .stats-summary {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons-bar {
                flex-wrap: wrap;
            }
            
            .stats-summary {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .doctors-table th,
            .doctors-table td {
                padding: 0.75rem;
            }
            
            .doctor-cell {
                min-width: 200px;
            }
        }
        
        @media (max-width: 576px) {
            .stats-summary {
                grid-template-columns: 1fr;
            }
            
            .pagination {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Stats Summary Cards with Loading Skeleton -->
    <div class="stats-summary" id="statsSummary">
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon-placeholder"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon-placeholder"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon-placeholder"></div>
        </div>
        <div class="stat-skeleton">
            <div class="skeleton-info">
                <div class="skeleton-line-sm"></div>
                <div class="skeleton-line-lg"></div>
            </div>
            <div class="skeleton-icon-placeholder"></div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="search-group">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, email, or specialty...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Specialty</label>
            <select id="specialtyFilter">
                <option value="all">All Specialties</option>
            </select>
        </div>
        <div class="action-buttons-bar">
            <button class="reset-btn" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
            <a href="{{ route('hospital.doctors.trash') }}" class="trash-btn">
                <i class="fas fa-trash-alt"></i> Trash
            </a>
            <a href="{{ route('hospital.doctors.create') }}" class="add-doctor-btn">
                <i class="fas fa-plus"></i> Add Doctor
            </a>
        </div>
    </div>

    <!-- Doctors Table -->
    <div class="doctors-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading doctors...</div>
        </div>
        <div id="tableContent">
            <table class="doctors-table">
                <thead>
                    <tr>
                        <th data-sort="name">Doctor <i class="fas fa-sort"></i></th>
                        <th data-sort="specialization">Specialty <i class="fas fa-sort"></i></th>
                        <th data-sort="fee">Fee <i class="fas fa-sort"></i></th>
                        <th data-sort="appointments">Patients <i class="fas fa-sort"></i></th>
                        <th>Status</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="doctorsTableBody">
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td colspan="7">
                                <div class="skeleton-row" style="display: flex; gap: 1rem; padding: 0.5rem;">
                                    <div class="skeleton-cell" style="height: 20px; flex: 2; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 0.5; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 0.5; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 0.5; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 0.5; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                    <div class="skeleton-cell" style="height: 20px; flex: 1; background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%); background-size: 200% 100%; border-radius: 6px; animation: shimmer 1.5s infinite;"></div>
                                </div>
                            </td>
                        </tr>
                    @endfor
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
    let currentSortField = 'name';
    let currentSortDir = 'asc';
    let currentSearch = '';
    let currentStatus = 'all';
    let currentSpecialty = 'all';
    let searchTimeout;
    let allDoctorsData = [];

    async function loadSpecialties() {
        try {
            const response = await fetch('{{ route("hospital.doctors.data") }}?per_page=1', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success && data.specialties) {
                const specialtySelect = document.getElementById('specialtyFilter');
                specialtySelect.innerHTML = '<option value="all">All Specialties</option>';
                data.specialties.forEach(specialty => {
                    specialtySelect.innerHTML += `<option value="${escapeHtml(specialty)}">${escapeHtml(specialty)}</option>`;
                });
            }
        } catch (error) {
            console.error('Error loading specialties:', error);
        }
    }

    async function loadDoctors() {
        const loadingContainer = document.getElementById('loadingContainer');
        const tableContent = document.getElementById('tableContent');
        
        loadingContainer.style.display = 'block';
        tableContent.style.display = 'none';

        try {
            const url = new URL('{{ route("hospital.doctors.data") }}');
            url.searchParams.set('page', currentPage);
            url.searchParams.set('sort_field', currentSortField);
            url.searchParams.set('sort_dir', currentSortDir);
            if (currentSearch) url.searchParams.set('search', currentSearch);
            if (currentStatus !== 'all') url.searchParams.set('status', currentStatus);
            if (currentSpecialty !== 'all') url.searchParams.set('specialty', currentSpecialty);

            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.success) {
                allDoctorsData = data.data;
                renderDoctorsTable(data.data);
                renderPagination(data);
                updateStatsSummary(data.data, data.total);
            } else {
                showEmptyState();
            }
        } catch (error) {
            console.error('Error loading doctors:', error);
            showEmptyState();
        } finally {
            loadingContainer.style.display = 'none';
            tableContent.style.display = 'block';
        }
    }

    function renderDoctorsTable(doctors) {
        const tbody = document.getElementById('doctorsTableBody');
        
        if (!doctors || doctors.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-user-md"></i>
                            <h3>No doctors found</h3>
                            <p>Try adjusting your search or filter criteria</p>
                            <button onclick="resetFilters()" class="btn btn-outline btn-sm">Reset Filters</button>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = doctors.map(doctor => `
            <tr>
                <td style="min-width: 220px;">
                    <div class="doctor-cell">
                        <div class="doctor-avatar">
                            ${doctor.avatar_html || `<span>${doctor.name.charAt(0)}</span>`}
                        </div>
                        <div class="doctor-info">
                            <div class="doctor-name">${escapeHtml(doctor.display_name || doctor.name)}</div>
                            <div class="doctor-email">${escapeHtml(doctor.email)}</div>
                        </div>
                    </div>
                </td>
                <td class="specialty-cell">${escapeHtml(doctor.specialization || 'Not specified')}</td>
                <td class="fee-value">${doctor.formatted_fee || 'N/A'}</td>
                <td class="patients-count">${doctor.appointments_count || 0}</td>
                <td>
                    <span class="status-badge ${doctor.is_active ? 'status-active' : 'status-inactive'}">
                        ${doctor.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td>
                    <span class="availability-badge ${doctor.is_available ? 'availability-available' : 'availability-unavailable'}">
                        ${doctor.is_available ? 'Available' : 'Unavailable'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="viewDoctor(${doctor.id})" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <a href="/hospital/doctors/${doctor.id}/edit" class="action-btn edit" title="Edit Doctor">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="action-btn toggle" onclick="toggleDoctorStatus(${doctor.id}, ${doctor.is_active})" title="${doctor.is_active ? 'Deactivate' : 'Activate'}">
                            <i class="fas ${doctor.is_active ? 'fa-ban' : 'fa-check-circle'}"></i> ${doctor.is_active ? 'Deactivate' : 'Activate'}
                        </button>
                        <button class="action-btn reset" onclick="resetDoctorPassword(${doctor.id})" title="Reset Password">
                            <i class="fas fa-key"></i> Reset
                        </button>
                        <button class="action-btn delete" onclick="deleteDoctor(${doctor.id})" title="Delete Doctor">
                            <i class="fas fa-trash-alt"></i> Delete
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

    function updateStatsSummary(doctors, total) {
        const activeCount = doctors.filter(d => d.is_active).length;
        const inactiveCount = doctors.filter(d => !d.is_active).length;
        
        const activeDoctors = doctors.filter(d => d.is_active);
        const avgFee = activeDoctors.length > 0 
            ? activeDoctors.reduce((sum, d) => sum + (parseFloat(d.consultation_fee) || 0), 0) / activeDoctors.length 
            : 0;
        
        const statsSummary = document.getElementById('statsSummary');
        statsSummary.innerHTML = `
            <div class="stat-summary-card">
                <div class="stat-summary-info">
                    <h4>Total Doctors</h4>
                    <div class="stat-summary-number">${total}</div>
                </div>
                <div class="stat-summary-icon"><i class="fas fa-user-md"></i></div>
            </div>
            <div class="stat-summary-card">
                <div class="stat-summary-info">
                    <h4>Active Doctors</h4>
                    <div class="stat-summary-number">${activeCount}</div>
                </div>
                <div class="stat-summary-icon"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-summary-card">
                <div class="stat-summary-info">
                    <h4>Inactive Doctors</h4>
                    <div class="stat-summary-number">${inactiveCount}</div>
                </div>
                <div class="stat-summary-icon"><i class="fas fa-ban"></i></div>
            </div>
            <div class="stat-summary-card">
                <div class="stat-summary-info">
                    <h4>Avg Consultation Fee</h4>
                    <div class="stat-summary-number">$${avgFee.toFixed(2)}</div>
                </div>
                <div class="stat-summary-icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        `;
    }

    function showEmptyState() {
        const tbody = document.getElementById('doctorsTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h3>No doctors found</h3>
                        <p>Try adjusting your search or filter criteria</p>
                        <button onclick="resetFilters()" class="btn btn-outline btn-sm">Reset Filters</button>
                    </div>
                </td>
            </tr>
        `;
    }

    function goToPage(page) {
        currentPage = page;
        loadDoctors();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('specialtyFilter').value = 'all';
        
        currentSearch = '';
        currentStatus = 'all';
        currentSpecialty = 'all';
        currentPage = 1;
        
        loadDoctors();
    }

    async function viewDoctor(id) {
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`/hospital/doctors/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.success) {
                const doc = data.doctor;
                
                Swal.fire({
                    title: `
                        <div style="display: flex; align-items: center; gap: 15px; justify-content: center;">
                            <div style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #2563eb, #1d4ed8); display: flex; align-items: center; justify-content: center;">
                                ${doc.avatar_html ? `<img src="${doc.avatar_url}" style="width: 100%; height: 100%; object-fit: cover;">` : `<span style="color: white; font-size: 28px; font-weight: 600;">${doc.name.charAt(0)}</span>`}
                            </div>
                            <div style="text-align: left;">
                                <div style="font-size: 1.3rem; font-weight: 700; color: #1f2937;">${escapeHtml(doc.name)}</div>
                                <div style="font-size: 0.85rem; color: #6b7280;">${escapeHtml(doc.specialization)}</div>
                            </div>
                        </div>
                    `,
                    html: `
                        <div style="text-align: left; margin-top: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                                <div style="background: #f3f4f6; padding: 12px; border-radius: 12px;">
                                    <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-dollar-sign"></i> Consultation Fee</div>
                                    <div style="font-size: 1.1rem; font-weight: 700; color: #10b981;">${escapeHtml(doc.consultation_fee)}</div>
                                </div>
                                <div style="background: #f3f4f6; padding: 12px; border-radius: 12px;">
                                    <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-briefcase"></i> Experience</div>
                                    <div style="font-size: 1.1rem; font-weight: 700;">${doc.experience} years</div>
                                </div>
                                <div style="background: #f3f4f6; padding: 12px; border-radius: 12px;">
                                    <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-star"></i> Rating</div>
                                    <div style="font-size: 1.1rem; font-weight: 700; color: #f59e0b;">⭐ ${doc.rating}</div>
                                </div>
                                <div style="background: #f3f4f6; padding: 12px; border-radius: 12px;">
                                    <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-users"></i> Total Patients</div>
                                    <div style="font-size: 1.1rem; font-weight: 700;">${doc.total_patients}</div>
                                </div>
                            </div>
                            <div style="margin-bottom: 0.75rem;">
                                <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-envelope"></i> Email</div>
                                <div style="font-size: 0.85rem; background: #f9fafb; padding: 8px 12px; border-radius: 8px;">${escapeHtml(doc.email)}</div>
                            </div>
                            <div style="margin-bottom: 0.75rem;">
                                <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-phone"></i> Phone</div>
                                <div style="font-size: 0.85rem; background: #f9fafb; padding: 8px 12px; border-radius: 8px;">${escapeHtml(doc.phone)}</div>
                            </div>
                            <div style="margin-bottom: 0.75rem;">
                                <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-venus-mars"></i> Gender / Age</div>
                                <div style="font-size: 0.85rem; background: #f9fafb; padding: 8px 12px; border-radius: 8px;">${escapeHtml(doc.gender)} / ${doc.age} years</div>
                            </div>
                            <div style="margin-top: 0.75rem; background: #f3f4f6; padding: 12px; border-radius: 12px;">
                                <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 4px;"><i class="fas fa-clock"></i> Working Hours</div>
                                <div style="font-size: 0.8rem;">${escapeHtml(doc.schedule)}</div>
                            </div>
                            <div style="margin-top: 0.5rem; font-size: 0.7rem; color: #6b7280; text-align: center;">
                                <i class="fas fa-calendar-alt"></i> Joined: ${escapeHtml(doc.joined_date)}
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Close',
                    width: '550px',
                    showCloseButton: true,
                });
            }
        } catch (error) {
            Swal.fire('Error', 'Could not load doctor details.', 'error');
        }
    }

    async function toggleDoctorStatus(id, currentStatus) {
        const action = currentStatus ? 'deactivate' : 'activate';
        
        Swal.fire({
            title: `${action === 'activate' ? 'Activate' : 'Deactivate'} Doctor?`,
            text: `Are you sure you want to ${action} this doctor?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'activate' ? '#10b981' : '#ef4444',
            confirmButtonText: `Yes, ${action}`,
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                try {
                    const response = await fetch(`/hospital/doctors/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success');
                        loadDoctors();
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                }
            }
        });
    }

    async function resetDoctorPassword(id) {
        Swal.fire({
            title: 'Reset Password?',
            text: 'A new password will be generated and sent to the doctor\'s email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                try {
                    const response = await fetch(`/hospital/doctors/${id}/reset-password`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Password Reset!', data.message, 'success');
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                }
            }
        });
    }

    async function deleteDoctor(id) {
        Swal.fire({
            title: 'Delete Doctor?',
            text: 'This doctor will be moved to trash. You can restore them from trash later.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                try {
                    const response = await fetch(`/hospital/doctors/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        loadDoctors();
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                }
            }
        });
    }

    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = document.getElementById('searchInput').value;
            currentPage = 1;
            loadDoctors();
        }, 300);
    }

    function setupSorting() {
        document.querySelectorAll('.doctors-table th[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const field = th.getAttribute('data-sort');
                if (currentSortField === field) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortField = field;
                    currentSortDir = 'asc';
                }
                currentPage = 1;
                loadDoctors();
            });
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

    document.addEventListener('DOMContentLoaded', function() {
        loadSpecialties();
        loadDoctors();
        setupSorting();
        
        document.getElementById('searchInput').addEventListener('input', debouncedSearch);
        document.getElementById('statusFilter').addEventListener('change', function() {
            currentStatus = this.value;
            currentPage = 1;
            loadDoctors();
        });
        document.getElementById('specialtyFilter').addEventListener('change', function() {
            currentSpecialty = this.value;
            currentPage = 1;
            loadDoctors();
        });
    });
</script>
@endpush