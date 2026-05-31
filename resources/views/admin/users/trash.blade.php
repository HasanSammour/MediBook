{{-- resources/views/admin/users/trash.blade.php --}}
@extends('layouts.admin')

@section('title', 'Trash - Deleted Users')
@section('page-title', 'Trash')
@section('page-subtitle', 'Restore or permanently delete users')

@push('styles')
    <style>
        .back-btn {
            background: #f3f4f6;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--dark-color);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #e5e7eb;
        }

        .users-table-container {
            background: var(--white);
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
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

        /* ===== USER CELL – STACKED NAME & EMAIL (same as index) ===== */
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
            background: #9ca3af;
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

        .user-info {
            flex: 1;
            display: block !important;
            white-space: normal !important;
        }

        .user-name,
        .user-email {
            display: block !important;
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

        /* Role badge */
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
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

        /* Other columns – one line */
        .phone-cell,
        .deleted-date,
        .action-buttons {
            white-space: nowrap;
        }

        .deleted-date {
            color: #dc2626;
            font-size: 0.8rem;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }

        .action-btn {
            background: #f3f4f6;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .action-btn.restore {
            color: #10b981;
        }

        .action-btn.restore:hover {
            background: rgba(16, 185, 129, 0.15);
        }

        .action-btn.force-delete {
            color: #dc2626;
        }

        .action-btn.force-delete:hover {
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

        /* Pagination with ellipsis */
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
                min-width: 750px;
            }
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.users.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>

    <div class="users-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading deleted users...</div>
        </div>
        <div id="tableContent">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">User</th>
                        <th style="width: 15%;">Role</th>
                        <th style="width: 15%;">Phone</th>
                        <th style="width: 20%;">Deleted Date</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody"></tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrapper" id="paginationWrapper"></div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let currentSearch = '';
        let searchTimeout;

        async function loadDeletedUsers() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');

            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            try {
                const url = new URL('{{ route("admin.users.data") }}');
                url.searchParams.set('page', currentPage);
                url.searchParams.set('trash', 'true');
                if (currentSearch) url.searchParams.set('search', currentSearch);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    renderTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading deleted users:', error);
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

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'numeric', year: 'numeric' });
        }

        function renderTable(users) {
            const tbody = document.getElementById('usersTableBody');

            if (!users || users.length === 0) {
                showEmptyState();
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
                        <td class="phone-cell">${escapeHtml(user.phone)}</td>
                        <td class="deleted-date">${formatDate(user.deleted_at)}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn restore" onclick="restoreUser(${user.id})">
                                    <i class="fas fa-trash-restore"></i> Restore
                                </button>
                                <button class="action-btn force-delete" onclick="forceDeleteUser(${user.id})">
                                    <i class="fas fa-trash-alt"></i> Permanent Delete
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
            loadDeletedUsers();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showEmptyState() {
            document.getElementById('usersTableBody').innerHTML = `
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-trash-alt"></i>
                                <h3>Trash is empty</h3>
                                <p>No deleted users found.</p>
                            </div>
                        </td>
                    </tr>
                `;
        }

        async function restoreUser(id) {
            Swal.fire({
                title: 'Restore User?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, restore'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', didOpen: () => Swal.showLoading() });
                    try {
                        const response = await fetch(`/admin/users/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Restored!', data.message, 'success');
                            loadDeletedUsers();
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Please try again.', 'error');
                    }
                }
            });
        }

        async function forceDeleteUser(id) {
            Swal.fire({
                title: 'Permanently Delete?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, permanently delete'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', didOpen: () => Swal.showLoading() });
                    try {
                        const response = await fetch(`/admin/users/${id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            loadDeletedUsers();
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
                currentSearch = document.getElementById('searchInput')?.value || '';
                currentPage = 1;
                loadDeletedUsers();
            }, 300);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadDeletedUsers();

            // Add search input dynamically to match filter bar style
            const filterBar = document.querySelector('.filter-bar');
            if (filterBar) {
                const searchDiv = document.createElement('div');
                searchDiv.className = 'search-group';
                searchDiv.style.flex = '2';
                searchDiv.style.position = 'relative';
                searchDiv.innerHTML = `
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                        <input type="text" id="searchInput" placeholder="Search deleted users..." style="width: 100%; padding: 10px 12px 10px 35px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.85rem;">
                    `;
                filterBar.prepend(searchDiv);
                document.getElementById('searchInput').addEventListener('input', debouncedSearch);
            }
        });
    </script>
@endpush