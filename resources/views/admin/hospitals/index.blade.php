{{-- resources/views/admin/hospitals/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Hospitals')
@section('page-title', 'Hospitals Management')
@section('page-subtitle', 'Manage all hospitals on the platform')

@push('styles')
    <style>
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
            min-width: 250px;
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
            font-size: 0.85rem;
        }
        
        .filter-group {
            min-width: 150px;
        }
        
        .filter-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
        }
        
        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
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
            font-size: 0.85rem;
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
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .trash-btn:hover {
            background: #fecaca;
        }
        
        .add-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .add-btn:hover {
            background: var(--primary-dark);
        }
        
        /* Hospitals Table */
        .hospitals-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            -webkit-overflow-scrolling: touch;
        }
        
        .hospitals-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }
        
        .hospitals-table th,
        .hospitals-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .hospitals-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        /* Hospital Cell - Name and Email in 2 lines */
        .hospital-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }
        
        .hospital-logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .hospital-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .hospital-logo i {
            font-size: 1.2rem;
            color: white;
        }
        
        .hospital-info {
            flex: 1;
        }
        
        .hospital-info .hospital-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .hospital-info .hospital-email {
            font-size: 0.7rem;
            color: var(--gray-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Other columns - one line */
        .phone-cell,
        .location-cell,
        .doctors-cell,
        .date-cell {
            white-space: nowrap;
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
        
        .action-btn.delete {
            color: #dc2626;
        }
        
        .action-btn.delete:hover {
            background: rgba(220, 38, 38, 0.15);
        }
        
        /* Loading Spinner */
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
        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-buttons-bar {
                flex-wrap: wrap;
            }
            
            .action-buttons {
                flex-wrap: wrap;
            }
            
            .hospital-cell {
                min-width: 180px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="search-group">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, email, phone or address...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="action-buttons-bar">
            <button class="reset-btn" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
            <a href="{{ route('admin.hospitals.trash') }}" class="trash-btn">
                <i class="fas fa-trash-alt"></i> Trash
            </a>
            <a href="{{ route('admin.hospitals.create') }}" class="add-btn">
                <i class="fas fa-plus"></i> Add Hospital
            </a>
        </div>
    </div>

    <!-- Hospitals Table -->
    <div class="hospitals-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading hospitals...</div>
        </div>
        <div id="tableContent">
            <table class="hospitals-table">
                <thead>
                    <tr>
                        <th data-sort="name">Hospital</th>
                        <th data-sort="phone">Phone</th>
                        <th data-sort="address">Location</th>
                        <th data-sort="doctors_count">Doctors</th>
                        <th>Status</th>
                        <th data-sort="created_at">Added Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="hospitalsTableBody">
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
    let currentSortField = 'created_at';
    let currentSortDir = 'desc';
    let currentSearch = '';
    let currentStatus = 'all';
    let searchTimeout;

    async function loadHospitals() {
        const loadingContainer = document.getElementById('loadingContainer');
        const tableContent = document.getElementById('tableContent');
        
        loadingContainer.style.display = 'block';
        tableContent.style.display = 'none';

        try {
            const url = new URL('{{ route("admin.hospitals.data") }}');
            url.searchParams.set('page', currentPage);
            url.searchParams.set('sort_field', currentSortField);
            url.searchParams.set('sort_dir', currentSortDir);
            if (currentSearch) url.searchParams.set('search', currentSearch);
            if (currentStatus !== 'all') url.searchParams.set('status', currentStatus);

            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.success) {
                renderHospitalsTable(data.data);
                renderPagination(data);
            } else {
                showEmptyState();
            }
        } catch (error) {
            console.error('Error loading hospitals:', error);
            showEmptyState();
        } finally {
            loadingContainer.style.display = 'none';
            tableContent.style.display = 'block';
        }
    }

    function renderHospitalsTable(hospitals) {
        const tbody = document.getElementById('hospitalsTableBody');
        
        if (!hospitals || hospitals.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-hospital"></i>
                            <h3>No hospitals found</h3>
                            <p>Try adjusting your search or filter criteria</p>
                            <button onclick="resetFilters()" class="btn btn-outline btn-sm">Reset Filters</button>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = hospitals.map(hospital => `
            <tr>
                <td>
                    <div class="hospital-cell">
                        <div class="hospital-logo">
                            ${hospital.logo_url ? `<img src="${hospital.logo_url}" alt="${escapeHtml(hospital.name)}">` : `<i class="fas fa-hospital"></i>`}
                        </div>
                        <div class="hospital-info">
                            <div class="hospital-name">${escapeHtml(hospital.name)}</div>
                            <div class="hospital-email">${escapeHtml(hospital.email)}</div>
                        </div>
                    </div>
                </td>
                <td class="phone-cell">${escapeHtml(hospital.phone)}</td>
                <td class="location-cell">${escapeHtml(hospital.location)}</td>
                <td class="doctors-cell">${hospital.doctors_count}</td>
                <td><span class="status-badge ${hospital.is_active ? 'status-active' : 'status-inactive'}">${hospital.is_active ? 'Active' : 'Inactive'}</span></td>
                <td class="date-cell">${hospital.created_at}</td>
                <td>
                    <div class="action-buttons">
                        <a href="/admin/hospitals/${hospital.id}/edit" class="action-btn edit" title="Edit Hospital">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="action-btn toggle" onclick="toggleHospitalStatus(${hospital.id}, ${hospital.is_active})" title="${hospital.is_active ? 'Deactivate' : 'Activate'}">
                            <i class="fas ${hospital.is_active ? 'fa-ban' : 'fa-check-circle'}"></i> ${hospital.is_active ? 'Deactivate' : 'Activate'}
                        </button>
                        <button class="action-btn delete" onclick="deleteHospital(${hospital.id})" title="Delete Hospital">
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

    function goToPage(page) {
        currentPage = page;
        loadHospitals();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        
        currentSearch = '';
        currentStatus = 'all';
        currentPage = 1;
        
        loadHospitals();
    }

    async function toggleHospitalStatus(id, currentStatus) {
        const action = currentStatus ? 'deactivate' : 'activate';
        
        Swal.fire({
            title: `${action === 'activate' ? 'Activate' : 'Deactivate'} Hospital?`,
            text: `Are you sure you want to ${action} this hospital?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'activate' ? '#10b981' : '#ef4444',
            confirmButtonText: `Yes, ${action}`,
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                try {
                    const response = await fetch(`/admin/hospitals/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Success!', data.message, 'success');
                        loadHospitals();
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                }
            }
        });
    }

    async function deleteHospital(id) {
        Swal.fire({
            title: 'Delete Hospital?',
            text: 'This hospital will be moved to trash. You can restore it from trash later.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                try {
                    const response = await fetch(`/admin/hospitals/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        loadHospitals();
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
            loadHospitals();
        }, 300);
    }

    function setupSorting() {
        document.querySelectorAll('.hospitals-table th[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const field = th.getAttribute('data-sort');
                if (currentSortField === field) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortField = field;
                    currentSortDir = 'asc';
                }
                currentPage = 1;
                loadHospitals();
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
        loadHospitals();
        setupSorting();
        
        document.getElementById('searchInput').addEventListener('input', debouncedSearch);
        document.getElementById('statusFilter').addEventListener('change', function() {
            currentStatus = this.value;
            currentPage = 1;
            loadHospitals();
        });
    });
</script>
@endpush