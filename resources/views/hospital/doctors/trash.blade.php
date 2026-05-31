{{-- resources/views/hospital/doctors/trash.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Trash - Deleted Doctors')
@section('page-title', 'Trash')
@section('page-subtitle', 'Restore or permanently delete doctors')

@push('styles')
    <style>
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
            min-width: 800px;
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
            font-size: 0.85rem;
        }

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
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doctor-avatar span {
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .doctor-info .doctor-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 4px;
        }

        .doctor-info .doctor-email {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .deleted-date {
            font-size: 0.8rem;
            color: #dc2626;
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

        @media (max-width: 768px) {

            .doctors-table th,
            .doctors-table td {
                padding: 0.75rem;
                font-size: 0.75rem;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('hospital.doctors.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Doctors
    </a>

    <div class="doctors-table-container">
        <div id="loadingContainer" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text" style="margin-top: 10px;">Loading deleted doctors...</div>
        </div>
        <div id="tableContent">
            <table class="doctors-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Email</th>
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="doctorsTableBody"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;
        let currentSearch = '';
        let searchTimeout;

        async function loadDeletedDoctors() {
            const loadingContainer = document.getElementById('loadingContainer');
            const tableContent = document.getElementById('tableContent');

            loadingContainer.style.display = 'block';
            tableContent.style.display = 'none';

            try {
                const url = new URL('{{ route("hospital.doctors.data") }}');
                url.searchParams.set('page', currentPage);
                url.searchParams.set('trash', 'true');
                if (currentSearch) url.searchParams.set('search', currentSearch);

                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success && data.data && data.data.length > 0) {
                    renderDoctorsTable(data.data);
                    renderPagination(data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading deleted doctors:', error);
                showEmptyState();
            } finally {
                loadingContainer.style.display = 'none';
                tableContent.style.display = 'block';
            }
        }

        function renderDoctorsTable(doctors) {
            const tbody = document.getElementById('doctorsTableBody');

            tbody.innerHTML = doctors.map(doctor => `
                <tr>
                    <td>
                        <div class="doctor-cell">
                            <div class="doctor-avatar">
                                ${doctor.avatar_html || `<span>${doctor.name.charAt(0)}</span>`}
                            </div>
                            <div class="doctor-info">
                                <div class="doctor-name">${escapeHtml(doctor.display_name || doctor.name)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(doctor.specialization || 'Not specified')}</td>
                    <td>${escapeHtml(doctor.email)}</td>
                    <td class="deleted-date">${doctor.deleted_at ? new Date(doctor.deleted_at).toLocaleDateString() : 'N/A'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn restore" onclick="restoreDoctor(${doctor.id})">
                                <i class="fas fa-trash-restore"></i> Restore
                            </button>
                            <button class="action-btn force-delete" onclick="forceDeleteDoctor(${doctor.id})">
                                <i class="fas fa-trash-alt"></i> Permanently Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(data) {
            const wrapper = document.getElementById('paginationWrapper');
            if (!wrapper) return;

            if (data.last_page <= 1) {
                wrapper.innerHTML = '';
                return;
            }

            let html = '<div class="pagination" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem;">';

            for (let i = 1; i <= data.last_page; i++) {
                html += `<button class="page-link ${i === data.current_page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            html += '</div>';
            if (document.getElementById('paginationWrapper')) {
                document.getElementById('paginationWrapper').innerHTML = html;
            } else {
                const wrapperDiv = document.createElement('div');
                wrapperDiv.id = 'paginationWrapper';
                wrapperDiv.className = 'pagination-wrapper';
                document.querySelector('.doctors-table-container').after(wrapperDiv);
                document.getElementById('paginationWrapper').innerHTML = html;
            }
        }

        function goToPage(page) {
            currentPage = page;
            loadDeletedDoctors();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showEmptyState() {
            const tbody = document.getElementById('doctorsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-trash-alt"></i>
                            <h3>Trash is empty</h3>
                            <p>No deleted doctors found.</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        async function restoreDoctor(id) {
            Swal.fire({
                title: 'Restore Doctor?',
                text: 'This doctor will be restored and visible in the doctors list.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, restore',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/hospital/doctors/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Restored!', data.message, 'success');
                            loadDeletedDoctors();
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Please try again.', 'error');
                    }
                }
            });
        }

        async function forceDeleteDoctor(id) {
            Swal.fire({
                title: 'Permanently Delete?',
                text: 'This action cannot be undone. The doctor will be permanently removed from the system.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, permanently delete',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const response = await fetch(`/hospital/doctors/${id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            loadDeletedDoctors();
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Please try again.', 'error');
                    }
                }
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

        document.addEventListener('DOMContentLoaded', function () {
            loadDeletedDoctors();

            // Add search input if needed
            const filterBar = document.querySelector('.filter-bar');
            if (filterBar) {
                const searchGroup = document.createElement('div');
                searchGroup.className = 'search-group';
                searchGroup.innerHTML = `
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search deleted doctors...">
                `;
                filterBar.prepend(searchGroup);

                document.getElementById('searchInput').addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        currentSearch = this.value;
                        currentPage = 1;
                        loadDeletedDoctors();
                    }, 300);
                });
            }
        });
    </script>
@endpush