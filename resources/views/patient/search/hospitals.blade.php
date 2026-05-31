@extends('layouts.patient')

@section('title', 'Find Hospitals')

@section('page-title', 'Find Hospitals')
@section('page-subtitle', 'Search and book appointments at top medical centers')

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
            grid-template-columns: 1fr 1fr auto;
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

        .filter-group input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            height: 46px;
        }

        .filter-group input:focus {
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

        /* Hospitals Grid */
        .hospitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .hospital-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hospital-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hospital-image {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .hospital-card:hover .hospital-image img {
            transform: scale(1.05);
        }

        /* Hospital Logo - Square Shape */
        .hospital-logo-square {
            width: 100px;
            height: 100px;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 3px solid white;
        }

        .hospital-logo-square img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hospital-logo-square svg {
            width: 100%;
            height: 100%;
        }

        .hospital-logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        }

        .hospital-badge {
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

        .hospital-info {
            padding: 1.25rem;
        }

        .hospital-name h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .hospital-address {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gray-color);
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .hospital-address i {
            color: var(--primary-color);
            width: 14px;
            font-size: 0.7rem;
        }

        .hospital-contact {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .hospital-contact span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .hospital-contact i {
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .hospital-stats {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-top: 1px solid #f0f0f0;
            margin-top: 0.5rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.65rem;
            color: var(--gray-color);
        }

        .hospital-actions {
            display: flex;
            gap: 0.75rem;
        }

        .hospital-actions .btn {
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

            .hospitals-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }

            .hospital-logo-square {
                width: 80px;
                height: 80px;
            }
        }

        @media (max-width: 768px) {
            .filters-card {
                padding: 1rem;
            }

            .hospital-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .hospital-actions {
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

            .hospital-logo-square {
                width: 70px;
                height: 70px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-group">
                <label>Hospital Name</label>
                <i class="fas fa-hospital"></i>
                <input type="text" id="searchInput" placeholder="Search by hospital name...">
            </div>
            <div class="filter-group">
                <label>Location</label>
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="locationInput" placeholder="City or area...">
            </div>
            <button class="search-btn" id="searchBtn">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Results Header -->
    <div class="results-header" id="resultsHeader" style="display: none;">
        <div class="results-count">
            Found <span id="resultsCount">0</span> hospitals
        </div>
        <select class="sort-select" id="sortSelect">
            <option value="name">Sort by Name</option>
            <option value="doctors">Sort by Doctors Count</option>
        </select>
    </div>

    <!-- Hospitals Grid -->
    <div id="hospitalsGrid">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading hospitals...</div>
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
        let currentSort = 'name';

        document.addEventListener('DOMContentLoaded', function () {
            loadHospitals();

            // Search button
            document.getElementById('searchBtn').addEventListener('click', function () {
                currentSearch = document.getElementById('searchInput').value;
                currentLocation = document.getElementById('locationInput').value;
                currentPage = 1;
                loadHospitals();
            });

            // Sort select
            document.getElementById('sortSelect').addEventListener('change', function () {
                currentSort = this.value;
                currentPage = 1;
                loadHospitals();
            });

            // Enter key on inputs
            document.getElementById('searchInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    currentSearch = this.value;
                    currentLocation = document.getElementById('locationInput').value;
                    currentPage = 1;
                    loadHospitals();
                }
            });

            document.getElementById('locationInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    currentSearch = document.getElementById('searchInput').value;
                    currentLocation = this.value;
                    currentPage = 1;
                    loadHospitals();
                }
            });
        });

        async function loadHospitals() {
            const grid = document.getElementById('hospitalsGrid');
            const resultsHeader = document.getElementById('resultsHeader');
            const paginationWrapper = document.getElementById('paginationWrapper');

            grid.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading hospitals...</div>
            </div>
        `;
            resultsHeader.style.display = 'none';
            paginationWrapper.style.display = 'none';

            try {
                const params = new URLSearchParams();
                if (currentSearch) params.append('search', currentSearch);
                if (currentLocation) params.append('location', currentLocation);
                params.append('sort', currentSort);
                params.append('page', currentPage);

                const response = await fetch(`{{ route('patient.search-hospitals.data') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    renderHospitals(data);
                    resultsHeader.style.display = 'flex';
                    paginationWrapper.style.display = 'flex';

                    document.getElementById('resultsCount').innerText = data.total;
                    document.getElementById('paginationInfo').innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;

                    renderPagination(data);
                }
            } catch (error) {
                console.error('Error loading hospitals:', error);
                grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Error loading hospitals</h3>
                    <p>Please try again later.</p>
                </div>
            `;
            }
        }

        function renderHospitals(data) {
            const grid = document.getElementById('hospitalsGrid');

            if (data.data.length === 0) {
                grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-hospital"></i>
                    <h3>No hospitals found</h3>
                    <p>Try adjusting your search criteria.</p>
                    <button class="btn btn-outline btn-sm mt-3" onclick="resetFilters()">Reset Filters</button>
                </div>
            `;
                return;
            }

            let html = '<div class="hospitals-grid">';

            data.data.forEach(hospital => {
                const logoUrl = hospital.logo_url || null;
                const isSvg = logoUrl && logoUrl.startsWith('data:image/svg+xml');
                const initial = hospital.name.charAt(0);

                html += `
                <div class="hospital-card">
                    <div class="hospital-image">
                        <div class="hospital-logo-square">
                            ${!isSvg && logoUrl ?
                        `<img src="${logoUrl}" alt="${hospital.name}" onerror="this.parentElement.innerHTML='<div class=\'hospital-logo-fallback\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\' viewBox=\'0 0 100 100\'><rect width=\'100\' height=\'100\' fill=\'#2563eb\' rx=\'16\'/><text x=\'50\' y=\'65\' text-anchor=\'middle\' fill=\'white\' font-size=\'45\'>🏥</text></svg></div>'">` :
                        `<div class="hospital-logo-fallback">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 100 100">
                                        <rect width="100" height="100" fill="#2563eb" rx="16"/>
                                        <text x="50" y="65" text-anchor="middle" fill="white" font-size="45">🏥</text>
                                    </svg>
                                </div>`
                    }
                        </div>
                        <div class="hospital-badge"><i class="fas fa-check-circle"></i> Verified</div>
                    </div>
                    <div class="hospital-info">
                        <div class="hospital-name">
                            <h3>${hospital.name}</h3>
                        </div>
                        <div class="hospital-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${hospital.address ? hospital.address.substring(0, 80) : 'Address not available'}</span>
                        </div>
                        <div class="hospital-contact">
                            <span><i class="fas fa-phone"></i> ${hospital.phone || 'N/A'}</span>
                            <span><i class="fas fa-envelope"></i> ${hospital.email || 'N/A'}</span>
                        </div>
                        <div class="hospital-stats">
                            <div class="stat-item">
                                <div class="stat-value"><i class="fas fa-user-md"></i> ${hospital.doctors_count || 0}</div>
                                <div class="stat-label">Doctors</div>
                            </div>
                        </div>
                        <div class="hospital-actions">
                            <button class="btn btn-outline btn-sm" onclick="viewHospitalDetails(${hospital.id})">View Details</button>
                            <button class="btn btn-primary btn-sm" onclick="bookAtHospital(${hospital.id})">Book Appointment</button>
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
            loadHospitals();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('locationInput').value = '';
            document.getElementById('sortSelect').value = 'name';

            currentSearch = '';
            currentLocation = '';
            currentSort = 'name';
            currentPage = 1;
            loadHospitals();
        }

        function viewHospitalDetails(id) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(`/patient/hospitals/${id}/details`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const hospital = data.hospital;
                        Swal.fire({
                            title: hospital.name,
                            html: `
                        <div style="text-align: left;">
                            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                <div style="width: 80px; height: 80px; border-radius: 16px; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                    ${hospital.logo_url ?
                                    `<img src="${hospital.logo_url}" alt="${hospital.name}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentElement.innerHTML='<i class=\'fas fa-hospital\' style=\'font-size: 2rem; color: #2563eb;\'></i>'">` :
                                    `<i class="fas fa-hospital" style="font-size: 2rem; color: #2563eb;"></i>`
                                }
                                </div>
                            </div>
                            <p><strong><i class="fas fa-map-marker-alt"></i> Address:</strong> ${hospital.address || 'N/A'}</p>
                            <p><strong><i class="fas fa-phone"></i> Phone:</strong> ${hospital.phone || 'N/A'}</p>
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${hospital.email || 'N/A'}</p>
                            <p><strong><i class="fas fa-user-md"></i> Total Doctors:</strong> ${hospital.doctors_count || 0}</p>
                        </div>
                    `,
                            icon: 'info',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'Close',
                            width: '450px'
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Could not load hospital details.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
        }

        function bookAtHospital(id) {
            window.location.href = `{{ route('patient.search-doctors') }}?hospital=${id}`;
        }
    </script>
@endpush