@extends('layouts.doctor')

@section('title', 'Patient History')

@section('page-title', 'Patient History')
@section('page-subtitle', 'View medical history of your patients')

@push('styles')
    <style>
        /* Search Bar */
        .search-bar {
            margin-bottom: 1.5rem;
        }

        .search-input {
            position: relative;
            max-width: 350px;
        }

        .search-input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }

        .search-input input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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

        /* Patients Grid */
        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .patient-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .patient-header {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            padding: 1rem 1.25rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .patient-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.2);
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

        .patient-avatar svg {
            width: 55px;
            height: 55px;
        }

        .patient-basic h3 {
            font-size: 1rem;
            margin-bottom: 0.2rem;
            color: white;
        }

        .patient-basic p {
            font-size: 0.7rem;
            opacity: 0.9;
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .patient-body {
            padding: 1.25rem;
        }

        .patient-info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
        }

        .patient-info-row i {
            width: 20px;
            color: var(--primary-color);
        }

        .patient-stats {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin: 0.75rem 0;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .stat {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.65rem;
            color: var(--gray-color);
        }

        .view-btn {
            width: 100%;
            padding: 8px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            background: var(--primary-dark);
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 60px;
        }

        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
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
            padding: 60px;
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

        /* Modal Styles - No inner scrollbar */
        .modal-content-custom {
            max-height: none !important;
            overflow: visible !important;
        }

        .swal2-popup {
            overflow: visible !important;
        }

        .appointment-history-item {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .appointment-history-item:last-child {
            margin-bottom: 0;
        }

        .appointment-history-date {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .appointment-history-diagnosis {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .appointment-history-notes {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .appointment-history-prescription {
            font-size: 0.7rem;
            background: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin-top: 0.5rem;
            border-left: 3px solid var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-input {
                max-width: 100%;
            }

            .patients-grid {
                grid-template-columns: 1fr;
            }

            .patient-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .stat {
                display: flex;
                justify-content: space-between;
            }

            .stat-value {
                font-size: 0.85rem;
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by patient name...">
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info" id="resultsInfo" style="display: none;">
        <div class="results-count">
            Showing <span id="resultsFrom">0</span> to <span id="resultsTo">0</span> of <span id="resultsTotal">0</span>
            patients
        </div>
    </div>

    <!-- Patients Grid -->
    <div id="patientsGrid">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading patients...</div>
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
        let currentSearch = '';
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function () {
            loadPatients();

            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadPatients();
                }, 500);
            });
        });

        async function loadPatients() {
            const grid = document.getElementById('patientsGrid');
            const resultsInfo = document.getElementById('resultsInfo');
            const paginationWrapper = document.getElementById('paginationWrapper');

            grid.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading patients...</div>
            </div>
        `;
            resultsInfo.style.display = 'none';
            paginationWrapper.style.display = 'none';

            try {
                const params = new URLSearchParams();
                if (currentSearch) params.append('search', currentSearch);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('doctor.patients.data') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (data.success) {
                    renderPatients(data);
                    resultsInfo.style.display = 'flex';
                    paginationWrapper.style.display = 'flex';

                    document.getElementById('resultsFrom').innerText = data.from || 0;
                    document.getElementById('resultsTo').innerText = data.to || 0;
                    document.getElementById('resultsTotal').innerText = data.total;
                    document.getElementById('paginationInfo').innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} patients`;

                    renderPagination(data);
                }
            } catch (error) {
                console.error('Error:', error);
                grid.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Error loading patients</h3><p>Please try again later.</p></div>`;
            }
        }

        function renderPatients(data) {
            const grid = document.getElementById('patientsGrid');
        
            if (data.data.length === 0) {
                grid.innerHTML = `<div class="empty-state"><i class="fas fa-users"></i><h3>No patients found</h3><p>Your patients will appear here after they complete appointments.</p></div>`;
                return;
            }
        
            let html = '<div class="patients-grid">';
        
            data.data.forEach(patient => {
                const avatarHtml = patient.avatar_html || `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"><span style="color: white; font-size: 1.5rem; font-weight: 600;">${patient.name.charAt(0)}</span></div>`;
                
                // Get gender icon and age display
                let genderIcon = '';
                if (patient.gender === 'male') {
                    genderIcon = '<i class="fas fa-mars"></i>';
                } else if (patient.gender === 'female') {
                    genderIcon = '<i class="fas fa-venus"></i>';
                } else if (patient.gender === 'other') {
                    genderIcon = '<i class="fas fa-genderless"></i>';
                }
                
                let ageDisplay = '';
                if (patient.age) {
                    ageDisplay = `<div class="patient-info-row"><i class="fas fa-birthday-cake"></i><span>${patient.age} years</span></div>`;
                }
        
                html += `
                    <div class="patient-card">
                        <div class="patient-header">
                            <div class="patient-avatar">
                                ${avatarHtml}
                            </div>
                            <div class="patient-basic">
                                <h3>${escapeHtml(patient.name)}</h3>
                                <p>Patient since ${patient.created_at ? new Date(patient.created_at).getFullYear() : 'N/A'}</p>
                            </div>
                        </div>
                        <div class="patient-body">
                            <div class="patient-info-row"><i class="fas fa-phone"></i><span>${escapeHtml(patient.phone || 'N/A')}</span></div>
                            <div class="patient-info-row"><i class="fas fa-envelope"></i><span>${escapeHtml(patient.email)}</span></div>
                            ${genderIcon ? `<div class="patient-info-row">${genderIcon}<span>${escapeHtml(patient.gender === 'male' ? 'Male' : patient.gender === 'female' ? 'Female' : 'Other')}</span></div>` : ''}
                            ${ageDisplay}
                            <div class="patient-stats">
                                <div class="stat"><div class="stat-value">${patient.total_visits || 0}</div><div class="stat-label">Visits</div></div>
                                <div class="stat"><div class="stat-value">${patient.last_visit || 'N/A'}</div><div class="stat-label">Last Visit</div></div>
                            </div>
                            <button class="view-btn" onclick="viewPatientHistory(${patient.id})">View Full History</button>
                        </div>
                    </div>
                `;
            });
        
            html += '</div>';
            grid.innerHTML = html;
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
            loadPatients();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        async function viewPatientHistory(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch(`/doctor/patients/${id}/history`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    const patient = data.patient;
                    const appointments = data.appointments;

                    let appointmentsHtml = '';
                    appointments.forEach(apt => {
                        appointmentsHtml += `
                            <div class="appointment-history-item">
                                <div class="appointment-history-date">📅 ${apt.date} at ${apt.time} | 🏥 ${escapeHtml(apt.hospital)}</div>
                                <div class="appointment-history-diagnosis">📋 Diagnosis: ${escapeHtml(apt.diagnosis)}</div>
                                <div class="appointment-history-notes">📝 Doctor's Notes: ${escapeHtml(apt.full_notes.substring(0, 200))}${apt.full_notes.length > 200 ? '...' : ''}</div>
                                ${apt.prescription ? `<div class="appointment-history-prescription">💊 Prescription: ${escapeHtml(apt.prescription)}</div>` : ''}
                                ${apt.patient_notes !== 'No notes provided' ? `<div class="appointment-history-notes" style="margin-top: 0.5rem;">👤 Patient's Notes: ${escapeHtml(apt.patient_notes)}</div>` : ''}
                            </div>
                        `;
                    });

                    // Build gender and age display
                    let genderAgeHtml = '';
                    if (patient.gender && patient.gender !== 'Not specified') {
                        genderAgeHtml += `<p><strong><i class="fas fa-venus-mars"></i> Gender:</strong> ${escapeHtml(patient.gender)}</p>`;
                    }
                    if (patient.age) {
                        genderAgeHtml += `<p><strong><i class="fas fa-birthday-cake"></i> Age:</strong> ${patient.age} years</p>`;
                    }

                    Swal.fire({
                        title: `<div style="display: flex; align-items: center; justify-content: center; gap: 10px;"><div style="width: 50px; height: 50px;">${patient.avatar_html || ''}</div><span>${escapeHtml(patient.name)}</span></div>`,
                        html: `
                            <div style="text-align: left; max-height: 60vh; overflow-y: auto; padding-right: 5px;">
                                <div style="background: #f9fafb; padding: 12px; border-radius: 12px; margin-bottom: 1rem;">
                                    ${genderAgeHtml}
                                    <p><strong><i class="fas fa-phone"></i> Phone:</strong> ${escapeHtml(patient.phone)}</p>
                                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${escapeHtml(patient.email)}</p>
                                    <p><strong><i class="fas fa-calendar"></i> Member Since:</strong> ${patient.registered_since}</p>
                                    <p><strong><i class="fas fa-stethoscope"></i> Total Visits:</strong> ${patient.total_visits}</p>
                                </div>
                                <h4 style="margin-bottom: 0.75rem;">📋 Medical History</h4>
                                ${appointmentsHtml || '<p>No medical history available.</p>'}
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Close',
                        width: '600px',
                        customClass: {
                            popup: 'modal-content-custom'
                        }
                    });
                } else {
                    Swal.fire('Error', data.message || 'Could not load patient history.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        }
    </script>
@endpush