@extends('layouts.patient')

@section('title', 'Find Doctors')

@section('page-title', 'Find Doctors')
@section('page-subtitle', 'Search and book appointments with top specialists')

@push('styles')
    <style>
        /* Search Filters */
        .filters-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-group {
            position: relative;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .filter-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            height: 46px;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 24px;
            height: 46px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .search-btn:hover {
            background: var(--primary-dark);
        }

        /* Results Header */
        .results-header {
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

        .sort-select {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            background: var(--white);
            cursor: pointer;
            height: 40px;
        }

        /* Doctors Grid */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .doctor-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Doctor Image - Circle Style */
        .doctor-image {
            position: relative;
            height: 180px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .doctor-avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 3px solid white;
        }

        .doctor-avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-avatar-circle svg {
            width: 100%;
            height: 100%;
        }

        .doctor-avatar-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        }

        .doctor-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.95);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .doctor-info {
            padding: 1.25rem;
        }

        .doctor-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .doctor-name h3 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .doctor-specialty {
            display: inline-block;
            background: #f0f9ff;
            color: var(--primary-color);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .doctor-hospital {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 0.75rem;
        }

        .doctor-hospital i {
            color: var(--primary-color);
            width: 16px;
            font-size: 0.8rem;
        }

        .doctor-stats {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 0.75rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.65rem;
            color: var(--gray-color);
        }

        .doctor-fee {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .doctor-actions {
            display: flex;
            gap: 0.75rem;
        }

        .doctor-actions .btn {
            flex: 1;
            font-size: 0.8rem;
            padding: 8px 12px;
        }

        /* Loading Spinner */
        .loading-container {
            text-align: center;
            padding: 40px;
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

        /* Responsive */
        @media (max-width: 968px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }

            .doctors-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .filters-card {
                padding: 1rem;
            }

            .doctor-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .doctor-actions {
                flex-direction: column;
            }

            .results-header {
                flex-direction: column;
                align-items: stretch;
            }

            .sort-select {
                width: 100%;
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .doctor-avatar-circle {
                width: 90px;
                height: 90px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Doctor Name / Specialty</label>
                <i class="fas fa-user-md"></i>
                <input type="text" id="searchInput" placeholder="Search by name or specialty...">
            </div>
            <div class="filter-group">
                <label>Location / Hospital</label>
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="locationInput" placeholder="City or hospital...">
            </div>
            <div class="filter-group">
                <label>Specialty</label>
                <i class="fas fa-stethoscope"></i>
                <select id="specialtySelect">
                    <option value="all">All Specialties</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                    @endforeach
                </select>
            </div>
            <button class="search-btn" id="searchBtn">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Results Header -->
    <div class="results-header" id="resultsHeader" style="display: none;">
        <div class="results-count">
            Found <span id="resultsCount">0</span> doctors
        </div>
        <select class="sort-select" id="sortSelect">
            <option value="name">Sort by Name A-Z</option>
            <option value="fee_asc">Sort by Fee (Low to High)</option>
            <option value="fee_desc">Sort by Fee (High to Low)</option>
        </select>
    </div>

    <!-- Doctors Grid -->
    <div id="doctorsGrid">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading doctors...</div>
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
        let currentLocation = '';
        let currentSpecialty = 'all';
        let currentSort = 'name';

        document.addEventListener('DOMContentLoaded', function () {
            loadDoctors();

            // Search button
            document.getElementById('searchBtn').addEventListener('click', function () {
                currentSearch = document.getElementById('searchInput').value;
                currentLocation = document.getElementById('locationInput').value;
                currentSpecialty = document.getElementById('specialtySelect').value;
                currentPage = 1;
                loadDoctors();
            });

            // Sort select
            document.getElementById('sortSelect').addEventListener('change', function () {
                currentSort = this.value;
                currentPage = 1;
                loadDoctors();
            });

            // Enter key on inputs
            document.getElementById('searchInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    currentSearch = this.value;
                    currentLocation = document.getElementById('locationInput').value;
                    currentSpecialty = document.getElementById('specialtySelect').value;
                    currentPage = 1;
                    loadDoctors();
                }
            });

            document.getElementById('locationInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    currentSearch = document.getElementById('searchInput').value;
                    currentLocation = this.value;
                    currentSpecialty = document.getElementById('specialtySelect').value;
                    currentPage = 1;
                    loadDoctors();
                }
            });
        });

        async function loadDoctors() {
            const grid = document.getElementById('doctorsGrid');
            const resultsHeader = document.getElementById('resultsHeader');
            const paginationWrapper = document.getElementById('paginationWrapper');

            grid.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading doctors...</div>
            </div>
        `;
            resultsHeader.style.display = 'none';
            paginationWrapper.style.display = 'none';

            try {
                const params = new URLSearchParams();
                if (currentSearch) params.append('search', currentSearch);
                if (currentLocation) params.append('location', currentLocation);
                if (currentSpecialty && currentSpecialty !== 'all') params.append('specialty', currentSpecialty);
                params.append('sort', currentSort);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('patient.search-doctors.data') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    renderDoctors(data);
                    resultsHeader.style.display = 'flex';
                    paginationWrapper.style.display = 'flex';

                    document.getElementById('resultsCount').innerText = data.total;
                    document.getElementById('paginationInfo').innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;

                    renderPagination(data);
                }
            } catch (error) {
                console.error('Error loading doctors:', error);
                grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Error loading doctors</h3>
                    <p>Please try again later.</p>
                </div>
            `;
            }
        }

        function renderDoctors(data) {
            const grid = document.getElementById('doctorsGrid');
            
            if (data.data.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h3>No doctors found</h3>
                        <p>Try adjusting your search criteria.</p>
                        <button class="btn btn-outline btn-sm mt-3" onclick="resetFilters()">Reset Filters</button>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="doctors-grid">';
            
            data.data.forEach(doctor => {
                // Use avatar_html from the controller (same as public doctors page)
                const avatarHtml = doctor.avatar_html || '';
                
                html += `
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <div class="doctor-avatar-circle">
                                ${avatarHtml ? avatarHtml : `<div class="doctor-avatar-fallback"><span style="font-size: 2rem; font-weight: 600; color: white;">${doctor.name.charAt(0)}</span></div>`}
                            </div>
                            <div class="doctor-badge"><i class="fas fa-check-circle"></i> Verified</div>
                        </div>
                        <div class="doctor-info">
                            <div class="doctor-header">
                                <div class="doctor-name">
                                    <h3>${doctor.name}</h3>
                                </div>
                            </div>
                            <span class="doctor-specialty">${doctor.specialization || 'General Physician'}</span>
                            <div class="doctor-hospital">
                                <i class="fas fa-hospital"></i>
                                <span>${doctor.hospital?.name || 'Independent Practice'}</span>
                            </div>
                            <div class="doctor-stats">
                                <div class="stat-item">
                                    <div class="stat-value">${doctor.experience}+</div>
                                    <div class="stat-label">Years Exp</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">${doctor.doctor_appointments_count || 0}+</div>
                                    <div class="stat-label">Patients</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">${doctor.calculated_rating || 4.5}</div>
                                    <div class="stat-label">Rating</div>
                                </div>
                            </div>
                            <div class="doctor-fee">Consultation Fee: $${doctor.consultation_fee || 100}</div>
                            <div class="doctor-actions">
                                <button class="btn btn-outline btn-sm" onclick="viewDoctorProfile(${doctor.id})">View Profile</button>
                                <button class="btn btn-primary btn-sm" onclick="bookAppointment(${doctor.id})">Book Now</button>
                            </div>
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
            loadDoctors();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('locationInput').value = '';
            document.getElementById('specialtySelect').value = 'all';
            document.getElementById('sortSelect').value = 'name';

            currentSearch = '';
            currentLocation = '';
            currentSpecialty = 'all';
            currentSort = 'name';
            currentPage = 1;
            loadDoctors();
        }

        async function viewDoctorProfile(id) {
            // Show loading
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch(`{{ url('patient/doctors') }}/${id}/profile`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const doctor = data.doctor;
                    
                    Swal.fire({
                        title: doctor.name,
                        html: `
                            <div style="text-align: left;">
                                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                    <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden;">
                                        ${doctor.avatar_html ? doctor.avatar_html : `<div style="width: 100%; height: 100%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;">${doctor.name.charAt(0)}</div>`}
                                    </div>
                                </div>
                                <p><strong><i class="fas fa-stethoscope"></i> Specialty:</strong> ${doctor.specialty || 'General Physician'}</p>
                                <p><strong><i class="fas fa-hospital"></i> Hospital:</strong> ${doctor.hospital}</p>
                                <p><strong><i class="fas fa-briefcase"></i> Experience:</strong> ${doctor.experience}+ years</p>
                                <p><strong><i class="fas fa-users"></i> Patients Treated:</strong> ${doctor.patients}+</p>
                                <p><strong><i class="fas fa-star"></i> Rating:</strong> ${doctor.rating}/5</p>
                                <p><strong><i class="fas fa-dollar-sign"></i> Consultation Fee:</strong> $${doctor.fee}</p>
                                <hr>
                                <p><strong><i class="fas fa-clock"></i> Availability:</strong></p>
                                <p style="font-size: 0.85rem; color: #6b7280;">${doctor.availability}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Close',
                        width: '480px'
                    });
                } else {
                    Swal.fire('Error', 'Could not load doctor details.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        }

        function bookAppointment(id) {
            window.location.href = `{{ url('patient/book') }}/${id}`;
        }
    </script>
@endpush