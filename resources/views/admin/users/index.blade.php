{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage all users on the platform')

@push('styles')
    <style>
        /* ===== FILTER BAR (same as before) ===== */
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

        /* ===== TABLE ===== */
        .users-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        .users-table th,
        .users-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .users-table th {
            font-weight: 600;
            color: var(--gray-color);
            background: #fafafa;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        /* ===== USER CELL – FORCE VERTICAL STACK ===== */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 200px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-avatar span {
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* CRITICAL FIX: remove any flex on .user-info, force block */
        .user-info {
            flex: 1;
            display: block !important;
            /* override any flex */
            white-space: normal !important;
            /* prevent inline forcing */
        }

        .user-name,
        .user-email {
            display: block !important;
            /* force each on new line */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 2px;
        }

        .user-email {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        /* ===== OTHER COLUMNS – ONE LINE ===== */
        .role-badge,
        .hospital-name,
        .phone-cell,
        .joined-date,
        .status-badge,
        .action-buttons {
            white-space: nowrap;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .role-system_admin {
            background: #8b5cf6;
            color: white;
        }

        .role-hospital_admin {
            background: #f59e0b;
            color: white;
        }

        .role-doctor {
            background: #3b82f6;
            color: white;
        }

        .role-patient {
            background: #10b981;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
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
            padding: 5px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
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

        /* Loading & empty states */
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
            align-items: center;
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
            font-size: 0.8rem;
            padding: 0 10px;
        }

        .page-link.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-dots {
            padding: 0 4px;
            color: #6b7280;
        }

        @media (max-width: 1024px) {
            .users-table {
                min-width: 850px;
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
        }
    </style>
@endpush

@section('content')
    <div class="filter-bar">
        <div class="search-group">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name or email...">
        </div>
        <div class="filter-group">
            <label>Role</label>
            <select id="roleFilter">
                <option value="all">All Roles</option>
                <option value="system_admin">System Admin</option>
                <option value="hospital_admin">Hospital Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>
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
            <label>Hospital</label>
            <select id="hospitalFilter">
                <option value="all">All Hospitals</option>
                @foreach($hospitals as $hospital)
                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="action-buttons-bar">
            <button class="reset-btn" onclick="resetFilters()"><i class="fas fa-undo-alt"></i> Reset</button>
            <a href="{{ route('admin.users.trash') }}" class="trash-btn"><i class="fas fa-trash-alt"></i> Trash</a>
            <a href="{{ route('admin.users.create') }}" class="add-btn"><i class="fas fa-plus"></i> Add User</a>
        </div>
    </div>

    <div class="users-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading users...</div>
        </div>
        <div id="tableContent">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">User</th>
                        <th style="width: 12%;">Role</th>
                        <th style="width: 20%;">Hospital</th>
                        <th style="width: 12%;">Phone</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 11%;">Joined Date</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- dynamic -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrapper" id="paginationWrapper"></div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let currentSearch = '';
        let currentRole = 'all';
        let currentStatus = 'all';
        let currentHospital = 'all';
        let searchTimeout;

        async function loadUsers() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');
            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            try {
                const url = new URL('{{ route("admin.users.data") }}');
                url.searchParams.set('page', currentPage);
                if (currentSearch) url.searchParams.set('search', currentSearch);
                if (currentRole !== 'all') url.searchParams.set('role', currentRole);
                if (currentStatus !== 'all') url.searchParams.set('status', currentStatus);
                if (currentHospital !== 'all') url.searchParams.set('hospital_id', currentHospital);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    renderUsersTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading users:', error);
                showEmptyState();
            } finally {
                loadingContainer.style.display = 'none';
                tableContent.style.display = 'block';
            }
        }

        function getRoleClass(role) {
            if (role === 'system_admin') return 'role-system_admin';
            if (role === 'hospital_admin') return 'role-hospital_admin';
            if (role === 'doctor') return 'role-doctor';
            return 'role-patient';
        }

        function getRoleDisplay(role) {
            if (role === 'system_admin') return 'System Admin';
            if (role === 'hospital_admin') return 'Hospital Admin';
            if (role === 'doctor') return 'Doctor';
            return 'Patient';
        }

        function renderUsersTable(users) {
            const tbody = document.getElementById('usersTableBody');
            if (!users || users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><h3>No users found</h3><p>Try adjusting your search or filter criteria</p></div></td></tr>`;
                return;
            }

            tbody.innerHTML = users.map(user => `
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        ${user.avatar_html || `<span>${user.name.charAt(0)}</span>`}
                                    </div>
                                    <div class="user-info">
                                        <div class="user-name">${escapeHtml(user.name)}</div>
                                        <div class="user-email">${escapeHtml(user.email)}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="role-badge ${getRoleClass(user.role)}">${getRoleDisplay(user.role)}</span></td>
                            <td class="hospital-name">${escapeHtml(user.hospital_name)}</td>
                            <td class="phone-cell">${escapeHtml(user.phone)}</td>
                            <td><span class="status-badge ${user.is_active ? 'status-active' : 'status-inactive'}">${user.is_active ? 'Active' : 'Inactive'}</span></td>
                            <td class="joined-date">${user.created_at}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/users/${user.id}/edit" class="action-btn edit" title="Edit User"><i class="fas fa-edit"></i> Edit</a>
                                    <button class="action-btn toggle" onclick="toggleUserStatus(${user.id}, ${user.is_active})" title="${user.is_active ? 'Deactivate' : 'Activate'}"><i class="fas ${user.is_active ? 'fa-ban' : 'fa-check-circle'}"></i> ${user.is_active ? 'Deactivate' : 'Activate'}</button>
                                    <button class="action-btn reset" onclick="resetUserPassword(${user.id})" title="Reset Password"><i class="fas fa-key"></i> Reset</button>
                                    <button class="action-btn delete" onclick="deleteUser(${user.id})" title="Delete User"><i class="fas fa-trash-alt"></i> Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
        }

        function renderPagination(data) {
            const wrapper = document.getElementById('paginationWrapper');
            if (data.last_page <= 1) { wrapper.innerHTML = ''; return; }
            let html = '<div class="pagination">';
            html += `<button class="page-link ${data.current_page === 1 ? 'disabled' : ''}" onclick="${data.current_page > 1 ? `goToPage(${data.current_page - 1})` : ''}" ${data.current_page === 1 ? 'disabled' : ''}>&laquo;</button>`;
            const start = Math.max(1, data.current_page - 2);
            const end = Math.min(data.last_page, data.current_page + 2);
            if (start > 1) {
                html += `<button class="page-link" onclick="goToPage(1)">1</button>`;
                if (start > 2) html += '<span class="pagination-dots">...</span>';
            }
            for (let i = start; i <= end; i++) {
                html += `<button class="page-link ${i === data.current_page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }
            if (end < data.last_page) {
                if (end < data.last_page - 1) html += '<span class="pagination-dots">...</span>';
                html += `<button class="page-link" onclick="goToPage(${data.last_page})">${data.last_page}</button>`;
            }
            html += `<button class="page-link ${data.current_page === data.last_page ? 'disabled' : ''}" onclick="${data.current_page < data.last_page ? `goToPage(${data.current_page + 1})` : ''}" ${data.current_page === data.last_page ? 'disabled' : ''}>&raquo;</button>`;
            html += '</div>';
            wrapper.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadUsers();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = 'all';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('hospitalFilter').value = 'all';
            currentSearch = '';
            currentRole = 'all';
            currentStatus = 'all';
            currentHospital = 'all';
            currentPage = 1;
            loadUsers();
        }

        async function toggleUserStatus(id, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            Swal.fire({
                title: `${action === 'activate' ? 'Activate' : 'Deactivate'} User?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'activate' ? '#10b981' : '#ef4444',
                confirmButtonText: `Yes, ${action}`
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    try {
                        const response = await fetch(`/admin/users/${id}/toggle-status`, {
                            method: 'PATCH',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Success!', data.message, 'success');
                            await loadUsers();
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Please try again.', 'error');
                    }
                }
            });
        }

        async function resetUserPassword(id) {
            Swal.fire({
                title: 'Reset Password?',
                text: 'A new password will be sent to the user\'s email.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Yes, reset'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    try {
                        const response = await fetch(`/admin/users/${id}/reset-password`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
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

        async function deleteUser(id) {
            Swal.fire({
                title: 'Delete User?',
                text: 'This user will be moved to trash.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    try {
                        const response = await fetch(`/admin/users/${id}`, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            await loadUsers();
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
                loadUsers();
            }, 300);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadUsers();
            document.getElementById('searchInput').addEventListener('input', debouncedSearch);
            document.getElementById('roleFilter').addEventListener('change', function () {
                currentRole = this.value;
                currentPage = 1;
                loadUsers();
            });
            document.getElementById('statusFilter').addEventListener('change', function () {
                currentStatus = this.value;
                currentPage = 1;
                loadUsers();
            });
            document.getElementById('hospitalFilter').addEventListener('change', function () {
                currentHospital = this.value;
                currentPage = 1;
                loadUsers();
            });
        });
    </script>
@endpush