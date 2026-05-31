@extends('layouts.guest')

@section('title', 'Find Expert Doctors - MediBook')

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
            min-width: 180px;
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

        .search-group input,
        .search-group select {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-group input:focus,
        .search-group select:focus {
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

        /* Doctors Grid */
        .doctors-section {
            padding: 60px 0;
            background: #f9fafb;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .doctor-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .doctor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        }

        .doctor-image {
            position: relative;
            height: 260px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .doctor-card:hover .doctor-image img {
            transform: scale(1.05);
        }

        .doctor-avatar-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-avatar-fallback svg {
            width: 100px;
            height: 100px;
        }

        .doctor-badge {
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

        .doctor-info {
            padding: 1.5rem;
        }

        .doctor-name h3 {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .doctor-specialty {
            display: inline-block;
            background: #f0f9ff;
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .doctor-hospital {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-color);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .doctor-hospital i {
            color: var(--primary-color);
            width: 16px;
        }

        .doctor-details {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 1rem;
        }

        .detail-item {
            text-align: center;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .detail-label {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .doctor-fee {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .doctor-actions {
            display: flex;
            gap: 1rem;
        }

        .doctor-actions .btn {
            flex: 1;
        }

        .doctor-availability {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .doctor-availability.available {
            background: #d1fae5;
            color: #065f46;
        }

        .doctor-availability.unavailable {
            background: #fee2e2;
            color: #991b1b;
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

        /* Doctor Info Section */
        .doctor-info-section {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
        }

        .doctor-info-section h2 {
            color: white;
            margin-bottom: 1rem;
        }

        .doctor-info-section p {
            margin-bottom: 2rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        .doctor-info-steps {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .info-step {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            text-align: left;
            flex: 1;
            min-width: 200px;
        }

        .info-step .step-number {
            font-size: 2rem;
            font-weight: 700;
            opacity: 0.5;
            margin-bottom: 0.5rem;
        }

        .info-step h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: white;
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

            .doctors-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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

            .doctor-details {
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

            .doctors-grid {
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
            <h1>Find Expert <span class="highlight">Doctors</span></h1>
            <p>Connect with top-rated specialists and book appointments instantly</p>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-form">
                    <div class="search-group">
                        <label>Doctor Name / Specialty</label>
                        <i class="fas fa-user-md"></i>
                        <input type="text" id="searchName" placeholder="Search by name or specialty..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="search-group">
                        <label>Location / Hospital</label>
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="searchLocation" placeholder="City or hospital..."
                            value="{{ request('location') }}">
                    </div>
                    <div class="search-group">
                        <label>Specialty</label>
                        <i class="fas fa-stethoscope"></i>
                        <select id="searchSpecialty">
                            <option value="all">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-buttons">
                        <button type="button" id="searchBtn" class="btn btn-primary"><i class="fas fa-search"></i>
                            Search</button>
                        <button type="button" id="clearBtn" class="btn-clear" style="display: {{ request('search') || request('location') || request('specialty') ? 'flex' : 'none' }}; align-items: center; justify-content: center; padding: 12px 20px; background: #f3f4f6; color: var(--dark-color); border-radius: 12px; border: none; cursor: pointer;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="doctors-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Expert Doctors</h2>
                <p>Choose from hundreds of qualified and experienced medical professionals</p>
            </div>

            <div class="results-header">
                <div class="results-count">
                    Found <span id="resultsCount">{{ $doctors->total() }}</span> doctors
                </div>
                <select class="sort-select" id="sortSelect">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name A-Z</option>
                    <option value="fee_asc" {{ request('sort') == 'fee_asc' ? 'selected' : '' }}>Sort by Fee (Low to High)
                    </option>
                    <option value="fee_desc" {{ request('sort') == 'fee_desc' ? 'selected' : '' }}>Sort by Fee (High to Low)
                    </option>
                </select>
            </div>

            <div id="doctorsGridContainer">
                @include('components.doctors-grid', ['doctors' => $doctors])
            </div>

            <div id="paginationContainer">
                @include('components.doctors-pagination', ['doctors' => $doctors])
            </div>
        </div>
    </section>

    @guest
        <section class="doctor-info-section">
            <div class="container">
                <h2>Are You a Doctor?</h2>
                <p>Doctors are added to MediBook by Hospital Administrators. Here's how to get listed:</p>
                <div class="doctor-info-steps">
                    <div class="info-step">
                        <div class="step-number">1</div>
                        <h4>Contact Your Hospital Admin</h4>
                    </div>
                    <div class="info-step">
                        <div class="step-number">2</div>
                        <h4>Provide Your Information</h4>
                    </div>
                    <div class="info-step">
                        <div class="step-number">3</div>
                        <h4>Get Listed & Start Managing</h4>
                    </div>
                </div>
            </div>
        </section>
    @endguest
@endsection

@push('scripts')
    <script>
        function showDoctorInfo(id, name, specialty, hospital, fee, isAvailable) {
            Swal.fire({
                title: name,
                html: `<div style="text-align:left">
                        <p><strong>Specialty:</strong> ${specialty}</p>
                        <p><strong>Hospital:</strong> ${hospital}</p>
                        <p><strong>Consultation Fee:</strong> $${fee}</p>
                        <p><strong>Status:</strong> ${isAvailable ? '<span style="color:#10b981">Available</span>' : '<span style="color:#ef4444">Unavailable</span>'}</p>
                    </div>`,
                icon: 'info',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Close'
            });
        }

        // View Profile function with auth check
        function viewDoctorProfile(id) {
            @auth
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch(`{{ url('patient/doctors') }}/${id}/profile`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            @else
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to view doctor profile',
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
        
        // Book Appointment function with auth check
        function bookDoctorAppointment(id) {
            @auth
                window.location.href = `{{ url('patient/book') }}/${id}`;
            @else
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
        async function fetchDoctors() {
            const searchName = document.getElementById('searchName')?.value || '';
            const searchLocation = document.getElementById('searchLocation')?.value || '';
            const searchSpecialty = document.getElementById('searchSpecialty')?.value || '';
            const sortValue = document.getElementById('sortSelect')?.value || 'name';

            const params = new URLSearchParams();
            if (searchName) params.append('search', searchName);
            if (searchLocation) params.append('location', searchLocation);
            if (searchSpecialty && searchSpecialty !== 'all') params.append('specialty', searchSpecialty);
            params.append('sort', sortValue);

            // Show or hide clear button based on filters
            const clearBtn = document.getElementById('clearBtn');
            if (searchName || searchLocation || (searchSpecialty && searchSpecialty !== 'all')) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }

            // Show loading
            document.getElementById('doctorsGridContainer').innerHTML = `<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i><p>Loading doctors...</p></div>`;

            try {
                const response = await fetch(`{{ route('doctors') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                document.getElementById('doctorsGridContainer').innerHTML = data.grid;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                document.getElementById('resultsCount').innerText = data.total;

                // Update URL without reload
                const newUrl = `{{ route('doctors') }}?${params.toString()}`;
                window.history.pushState({}, '', newUrl);

            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('searchName').value = '';
            document.getElementById('searchLocation').value = '';
            document.getElementById('searchSpecialty').value = 'all';
            document.getElementById('sortSelect').value = 'name';

            // Hide clear button
            document.getElementById('clearBtn').style.display = 'none';

            // Fetch doctors with empty filters
            fetchDoctors();
        }

        // Event Listeners
        document.getElementById('searchBtn')?.addEventListener('click', fetchDoctors);
        document.getElementById('sortSelect')?.addEventListener('change', fetchDoctors);
        document.getElementById('searchSpecialty')?.addEventListener('change', fetchDoctors);
        document.getElementById('clearBtn')?.addEventListener('click', clearFilters);

        // Search on Enter key
        const searchName = document.getElementById('searchName');
        const searchLocation = document.getElementById('searchLocation');

        searchName?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') fetchDoctors();
        });

        searchLocation?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') fetchDoctors();
        });
    </script>
@endpush