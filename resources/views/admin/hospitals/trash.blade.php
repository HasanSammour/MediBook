{{-- resources/views/admin/hospitals/trash.blade.php --}}
@extends('layouts.admin')

@section('title', 'Trash - Deleted Hospitals')
@section('page-title', 'Trash')
@section('page-subtitle', 'Restore or permanently delete hospitals')

@push('styles')
    <style>
        .back-btn {
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #e5e7eb;
        }

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
            min-width: 800px;
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
        }

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
            background: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .deleted-date {
            color: #dc2626;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
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

        @media (max-width: 768px) {
            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.hospitals.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Hospitals
    </a>

    <div class="hospitals-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading deleted hospitals...</div>
        </div>
        <div id="tableContent">
            <table class="hospitals-table">
                <thead>
                    <tr>
                        <th>Hospital</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="hospitalsTableBody"></tbody>
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

        async function loadDeletedHospitals() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');

            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            try {
                const url = new URL('{{ route("admin.hospitals.data") }}');
                url.searchParams.set('page', currentPage);
                url.searchParams.set('trash', 'true');
                if (currentSearch) url.searchParams.set('search', currentSearch);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    renderHospitalsTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading deleted hospitals:', error);
                showEmptyState();
            } finally {
                loadingContainer.style.display = 'none';
                tableContent.style.display = 'block';
            }
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'numeric', year: 'numeric' });
        }

        function renderHospitalsTable(hospitals) {
            const tbody = document.getElementById('hospitalsTableBody');

            if (!hospitals || hospitals.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-trash-alt"></i>
                                <h3>Trash is empty</h3>
                                <p>No deleted hospitals found.</p>
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
                                ${hospital.logo_url ? `<img src="${hospital.logo_url}" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="fas fa-hospital"></i>`}
                            </div>
                            <div class="hospital-info">
                                <div class="hospital-name">${escapeHtml(hospital.name)}</div>
                                <div class="hospital-email">${escapeHtml(hospital.email)}</div>
                            </div>
                        </div>
                    </td>
                    <td class="phone-cell">${escapeHtml(hospital.phone)}</td>
                    <td class="location-cell">${escapeHtml(hospital.location)}</td>
                    <td class="deleted-date">${formatDate(hospital.deleted_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn restore" onclick="restoreHospital(${hospital.id})">
                                <i class="fas fa-trash-restore"></i> Restore
                            </button>
                            <button class="action-btn force-delete" onclick="forceDeleteHospital(${hospital.id})">
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

            for (let i = 1; i <= data.last_page; i++) {
                html += `<button class="page-link ${i === data.current_page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            html += '</div>';
            wrapper.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadDeletedHospitals();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showEmptyState() {
            const tbody = document.getElementById('hospitalsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-trash-alt"></i>
                            <h3>Trash is empty</h3>
                            <p>No deleted hospitals found.</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        async function restoreHospital(id) {
            Swal.fire({
                title: 'Restore Hospital?',
                text: 'This hospital will be restored and visible in the hospitals list.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, restore',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/admin/hospitals/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Restored!', data.message, 'success');
                            loadDeletedHospitals();
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Please try again.', 'error');
                    }
                }
            });
        }

        async function forceDeleteHospital(id) {
            Swal.fire({
                title: 'Permanently Delete?',
                text: 'This action cannot be undone. The hospital will be permanently removed from the system.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, permanently delete',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/admin/hospitals/${id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            loadDeletedHospitals();
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
                loadDeletedHospitals();
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
            loadDeletedHospitals();

            // Add search input to filter bar
            const filterBar = document.querySelector('.filter-bar');
            if (filterBar) {
                const searchDiv = document.createElement('div');
                searchDiv.className = 'search-group';
                searchDiv.style.flex = '2';
                searchDiv.style.position = 'relative';
                searchDiv.innerHTML = `
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                    <input type="text" id="searchInput" placeholder="Search deleted hospitals..." style="width: 100%; padding: 10px 12px 10px 35px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.85rem;">
                `;
                filterBar.prepend(searchDiv);

                document.getElementById('searchInput').addEventListener('input', debouncedSearch);
            }
        });
    </script>
@endpush