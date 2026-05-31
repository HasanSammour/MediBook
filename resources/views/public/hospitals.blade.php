@extends('layouts.guest')

@section('title', 'Find Hospitals - MediBook')

@push('styles')
    <style>
        /* Hero Section with Animation */
        .page-header {
            padding: 120px 0 60px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: slideBackground 20s linear infinite;
        }

        @keyframes slideBackground {
            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(40px, 40px);
            }
        }

        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: fadeInDown 0.8s ease;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            font-size: 1.125rem;
            color: var(--gray-color);
            max-width: 600px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Search Section */
        .search-section {
            padding: 40px 0 0;
            background: var(--white);
        }

        .search-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .search-group {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .search-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .search-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 0.9rem;
        }

        .search-group input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .search-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .search-buttons button {
            padding: 12px 20px;
            font-size: 0.9rem;
            border-radius: 12px;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-clear {
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
        }

        .btn-clear:hover {
            background: #e5e7eb;
        }

        /* Results Header */
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--gray-color);
            font-size: 0.9rem;
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
        }

        /* Hospitals Grid */
        .hospitals-section {
            padding: 60px 0;
            background: #f9fafb;
        }

        .hospitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .hospital-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hospital-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        }

        .hospital-image {
            position: relative;
            height: 200px;
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

        .hospital-logo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hospital-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .hospital-info {
            padding: 1.5rem;
        }

        .hospital-name h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .hospital-address {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-color);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .hospital-address i {
            color: var(--primary-color);
            width: 16px;
        }

        .hospital-contact {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .hospital-contact span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        .hospital-contact i {
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .hospital-stats {
            display: flex;
            justify-content: flex-start;
            gap: 2rem;
            padding: 1rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .hospital-actions {
            display: flex;
            gap: 1rem;
        }

        .hospital-actions .btn {
            flex: 1;
        }

        /* Loading Spinner */
        .loading-spinner {
            text-align: center;
            padding: 60px;
            background: var(--white);
            border-radius: 20px;
            grid-column: 1/-1;
        }

        .loading-spinner i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: block;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px;
            background: var(--white);
            border-radius: 20px;
            grid-column: 1/-1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        /* Pagination Container */
        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-container {
            display: flex;
            justify-content: flex-end;
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
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            background: var(--white);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            color: var(--dark-color);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
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

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* CTA Section */
        .cta-section {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
        }

        .cta-content h2 {
            color: white;
            margin-bottom: 1rem;
        }

        .cta-content p {
            margin-bottom: 2rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        .pagination-info {
            color: var(--gray-color);
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .search-form {
                flex-direction: column;
            }

            .search-group {
                width: 100%;
            }

            .search-buttons {
                width: 100%;
            }

            .search-buttons button {
                flex: 1;
            }

            .hospitals-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 100px 0 40px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .hospital-stats {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
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

            .page-link {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .hospitals-grid {
                grid-template-columns: 1fr;
            }

            .page-link {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
                font-size: 0.7rem;
            }

            .pagination-info {
                font-size: 0.7rem;
            }
        }
    </style>
@endpush

@section('content')
    <section class="page-header">
        <div class="container">
            <h1>Find the Best <span class="highlight">Hospitals</span></h1>
            <p>Discover top-rated medical centers near you and get the care you deserve</p>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-form">
                    <div class="search-group">
                        <label>Hospital Name</label>
                        <i class="fas fa-hospital"></i>
                        <input type="text" id="searchName" placeholder="Search by hospital name..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="search-group">
                        <label>Location</label>
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="searchLocation" placeholder="City or area..."
                            value="{{ request('location') }}">
                    </div>
                    <div class="search-buttons">
                        <button type="button" id="searchBtn" class="btn btn-primary"><i class="fas fa-search"></i>
                            Search</button>
                         <button type="button" id="clearBtn" class="btn-clear" style="display: {{ request('search') || request('location') ? 'flex' : 'none' }}; align-items: center; justify-content: center; padding: 12px 20px; background: #f3f4f6; color: var(--dark-color); border-radius: 12px; border: none; cursor: pointer;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hospitals-section">
        <div class="container">
            <div class="section-header">
                <h2>Top Rated Hospitals</h2>
                <p>Partnered with the best healthcare institutions</p>
            </div>

            <div class="results-header">
                <div class="results-count">
                    Found <span id="resultsCount">{{ $hospitals->total() }}</span> hospitals
                </div>
                <select class="sort-select" id="sortSelect">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="doctors" {{ request('sort') == 'doctors' ? 'selected' : '' }}>Sort by Doctors Count
                    </option>
                </select>
            </div>

            <div id="hospitalsGridContainer">
                @include('components.hospitals-grid', ['hospitals' => $hospitals])
            </div>

            <div id="paginationContainer">
                @include('components.hospitals-pagination', ['hospitals' => $hospitals])
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Want to List Your Hospital?</h2>
                <p>Only System Administrators can add hospitals to the platform. Contact our admin team to get your hospital
                    listed.</p>
                <a href="mailto:admin@medibook.com" class="btn btn-primary btn-large">Contact System Admin</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function showHospitalInfo(id, name, address, phone, email, doctorsCount) {
            Swal.fire({
                title: name,
                html: `<div style="text-align:left">
                    <p><strong>Address:</strong> ${address}</p>
                    <p><strong>Phone:</strong> ${phone}</p>
                    <p><strong>Email:</strong> ${email}</p>
                    <p><strong>Doctors:</strong> ${doctorsCount}+</p>
                </div>`,
                icon: 'info',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Close'
            });
        }

        function viewHospitalDetails(id) {
            @auth
                // User is logged in - fetch hospital details
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
            @else
                // User is not logged in - show login prompt
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to view hospital details',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Login Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
            @endauth
        }

        // Book at Hospital function with auth check
        function bookAtHospital(id) {
            @auth
                // User is logged in - redirect to find doctors with hospital filter
                window.location.href = `{{ route('patient.search-doctors') }}?hospital=${id}`;
            @else
                // User is not logged in - show login prompt
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to book an appointment',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Login Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
            @endauth
        }

        // AJAX Search and Filter Function
        async function fetchHospitals() {
            const searchName = document.getElementById('searchName')?.value || '';
            const searchLocation = document.getElementById('searchLocation')?.value || '';
            const sortValue = document.getElementById('sortSelect')?.value || 'name';

            const params = new URLSearchParams();
            if (searchName) params.append('search', searchName);
            if (searchLocation) params.append('location', searchLocation);
            params.append('sort', sortValue);

            // Show or hide clear button based on filters
            const clearBtn = document.getElementById('clearBtn');
            if (searchName || searchLocation) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }

            // Show loading
            document.getElementById('hospitalsGridContainer').innerHTML = `<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i><p>Loading hospitals...</p></div>`;

            try {
                const response = await fetch(`{{ route('hospitals') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                document.getElementById('hospitalsGridContainer').innerHTML = data.grid;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                document.getElementById('resultsCount').innerText = data.total;

                // Update URL without reload
                const newUrl = `{{ route('hospitals') }}?${params.toString()}`;
                window.history.pushState({}, '', newUrl);

            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('searchName').value = '';
            document.getElementById('searchLocation').value = '';
            document.getElementById('sortSelect').value = 'name';

            // Hide clear button
            document.getElementById('clearBtn').style.display = 'none';

            // Fetch hospitals with empty filters
            fetchHospitals();
        }

        // Event Listeners
        document.getElementById('searchBtn')?.addEventListener('click', fetchHospitals);
        document.getElementById('sortSelect')?.addEventListener('change', fetchHospitals);
        document.getElementById('clearBtn')?.addEventListener('click', clearFilters);

        // Search on Enter key
        const searchName = document.getElementById('searchName');
        const searchLocation = document.getElementById('searchLocation');

        searchName?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') fetchHospitals();
        });

        searchLocation?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') fetchHospitals();
        });
    </script>
@endpush