{{-- resources/views/hospital/profile/show.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Hospital Profile')
@section('page-title', 'Hospital Profile')
@section('page-subtitle', 'View your hospital information and statistics')

@push('styles')
    <style>
        /* Profile Container */
        .profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 1.5rem;
        }
        
        /* Profile Sidebar */
        .profile-sidebar {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            height: fit-content;
        }
        
        .hospital-logo {
            width: 150px;
            height: 150px;
            margin: 0 auto 1rem;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .hospital-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .hospital-logo i {
            font-size: 3rem;
            color: white;
        }
        
        .hospital-name-sidebar {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .hospital-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .edit-btn {
            width: 100%;
            margin-top: 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .edit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-info h4 {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        /* Loading Skeleton for Stats */
        .stat-skeleton {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .skeleton-info {
            flex: 1;
        }
        
        .skeleton-line-sm {
            height: 10px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            margin-bottom: 8px;
            width: 60%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-line-lg {
            height: 24px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            width: 70%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 12px;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Information Card Loading */
        .info-skeleton {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .skeleton-title {
            height: 20px;
            width: 150px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .skeleton-label {
            width: 120px;
            height: 16px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-value {
            flex: 1;
            height: 16px;
            margin-left: 1rem;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            animation: shimmer 1.5s infinite;
        }
        
        /* Recent Doctors Skeleton */
        .doctors-skeleton {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .skeleton-doctor-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .skeleton-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 50%;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-doctor-info {
            flex: 1;
        }
        
        .skeleton-doctor-name {
            height: 14px;
            width: 60%;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            margin-bottom: 8px;
            animation: shimmer 1.5s infinite;
        }
        
        .skeleton-doctor-specialty {
            height: 10px;
            width: 40%;
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            border-radius: 6px;
            animation: shimmer 1.5s infinite;
        }
        
        /* Information Card */
        .info-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .info-card h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-label {
            width: 120px;
            font-weight: 600;
            color: var(--gray-color);
            font-size: 0.8rem;
        }
        
        .info-value {
            flex: 1;
            color: var(--dark-color);
            font-size: 0.85rem;
        }
        
        /* Recent Doctors */
        .doctors-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .doctors-card h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        .doctor-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .doctor-item:last-child {
            border-bottom: none;
        }
        
        .doctor-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .doctor-info h4 {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }
        
        .doctor-info p {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }
        
        .view-all-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .view-all-link:hover {
            text-decoration: underline;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray-color);
        }
        
        .empty-state i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        /* Loading Container */
        .loading-container {
            text-align: center;
            padding: 20px;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 968px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                display: flex;
                align-items: center;
                text-align: left;
                gap: 1.5rem;
                flex-wrap: wrap;
            }
            
            .hospital-logo {
                margin: 0;
                width: 100px;
                height: 100px;
            }
            
            .sidebar-content {
                flex: 1;
            }
            
            .edit-btn {
                width: auto;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }
            
            .profile-sidebar {
                flex-direction: column;
                text-align: center;
            }
            
            .hospital-logo {
                margin: 0 auto;
            }
            
            .edit-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <!-- Profile Sidebar (Static - No AJAX needed, just direct display) -->
        <div class="profile-sidebar">
            <div class="hospital-logo">
                @if($hospital->logo && file_exists(public_path($hospital->logo)))
                    <img src="{{ asset($hospital->logo) }}" alt="{{ $hospital->name }}">
                @else
                    <i class="fas fa-hospital"></i>
                @endif
            </div>
            <div class="sidebar-content">
                <div class="hospital-name-sidebar">{{ $hospital->name }}</div>
                <span class="hospital-status status-active">Active</span>
                <a href="{{ route('hospital.profile.edit') }}" class="edit-btn">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
        
        <!-- Main Content with AJAX Loading -->
        <div class="profile-main">
            <!-- Stats Cards - Loading Skeletons -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-skeleton">
                    <div class="skeleton-info">
                        <div class="skeleton-line-sm"></div>
                        <div class="skeleton-line-lg"></div>
                    </div>
                    <div class="skeleton-icon"></div>
                </div>
                <div class="stat-skeleton">
                    <div class="skeleton-info">
                        <div class="skeleton-line-sm"></div>
                        <div class="skeleton-line-lg"></div>
                    </div>
                    <div class="skeleton-icon"></div>
                </div>
                <div class="stat-skeleton">
                    <div class="skeleton-info">
                        <div class="skeleton-line-sm"></div>
                        <div class="skeleton-line-lg"></div>
                    </div>
                    <div class="skeleton-icon"></div>
                </div>
            </div>
            
            <!-- Information Card - Loading Skeleton -->
            <div class="info-skeleton" id="infoSkeleton">
                <div class="skeleton-title"></div>
                <div class="skeleton-row">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton-label"></div>
                    <div class="skeleton-value"></div>
                </div>
            </div>
            
            <!-- Information Card - Hidden until loaded -->
            <div class="info-card" id="infoCard" style="display: none;">
                <h3><i class="fas fa-building"></i> Hospital Information</h3>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                    <div class="info-value" id="infoAddress">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-phone"></i> Phone</div>
                    <div class="info-value" id="infoPhone">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                    <div class="info-value" id="infoEmail">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar-alt"></i> Active Since</div>
                    <div class="info-value" id="infoActiveSince">-</div>
                </div>
            </div>
            
            <!-- Recent Doctors Card - Loading Skeleton -->
            <div class="doctors-skeleton" id="doctorsSkeleton">
                <div class="skeleton-title"></div>
                <div class="skeleton-doctor-item">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-doctor-info">
                        <div class="skeleton-doctor-name"></div>
                        <div class="skeleton-doctor-specialty"></div>
                    </div>
                </div>
                <div class="skeleton-doctor-item">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-doctor-info">
                        <div class="skeleton-doctor-name"></div>
                        <div class="skeleton-doctor-specialty"></div>
                    </div>
                </div>
                <div class="skeleton-doctor-item">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-doctor-info">
                        <div class="skeleton-doctor-name"></div>
                        <div class="skeleton-doctor-specialty"></div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Doctors Card - Hidden until loaded -->
            <div class="doctors-card" id="doctorsCard" style="display: none;">
                <h3><i class="fas fa-user-md"></i> Recent Doctors</h3>
                <div id="recentDoctorsList"></div>
                <a href="{{ route('hospital.doctors.index') }}" class="view-all-link" id="viewAllLink" style="display: none;">
                    View All Doctors <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Hospital ID from PHP
    const hospitalId = {{ $hospital->id }};
    
    document.addEventListener('DOMContentLoaded', function() {
        loadStats();
        loadHospitalInfo();
        loadRecentDoctors();
    });
    
    async function loadStats() {
        const statsGrid = document.getElementById('statsGrid');
        
        try {
            // Fetch stats from API (you need to create this endpoint)
            const response = await fetch(`/hospital/profile/stats`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            if (data.success) {
                statsGrid.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Doctors</h4>
                            <div class="stat-value">${data.stats.total_doctors.toLocaleString()}</div>
                        </div>
                        <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Appointments</h4>
                            <div class="stat-value">${data.stats.total_appointments.toLocaleString()}</div>
                        </div>
                        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Patients</h4>
                            <div class="stat-value">${data.stats.total_patients.toLocaleString()}</div>
                        </div>
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
            statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Doctors</h4>
                        <div class="stat-value">0</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Appointments</h4>
                        <div class="stat-value">0</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Patients</h4>
                        <div class="stat-value">0</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
            `;
        }
    }
    
    async function loadHospitalInfo() {
        const infoSkeleton = document.getElementById('infoSkeleton');
        const infoCard = document.getElementById('infoCard');
        
        try {
            const response = await fetch(`/hospital/profile/info`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('infoAddress').innerHTML = escapeHtml(data.hospital.address);
                document.getElementById('infoPhone').innerHTML = escapeHtml(data.hospital.phone);
                document.getElementById('infoEmail').innerHTML = escapeHtml(data.hospital.email);
                document.getElementById('infoActiveSince').innerHTML = escapeHtml(data.hospital.active_since);
                
                infoSkeleton.style.display = 'none';
                infoCard.style.display = 'block';
            }
        } catch (error) {
            console.error('Error loading hospital info:', error);
            infoSkeleton.style.display = 'none';
            infoCard.style.display = 'block';
            document.getElementById('infoAddress').innerHTML = 'Error loading data';
            document.getElementById('infoPhone').innerHTML = 'Error loading data';
            document.getElementById('infoEmail').innerHTML = 'Error loading data';
            document.getElementById('infoActiveSince').innerHTML = 'Error loading data';
        }
    }
    
    async function loadRecentDoctors() {
        const doctorsSkeleton = document.getElementById('doctorsSkeleton');
        const doctorsCard = document.getElementById('doctorsCard');
        const doctorsList = document.getElementById('recentDoctorsList');
        const viewAllLink = document.getElementById('viewAllLink');
        
        try {
            const response = await fetch(`/hospital/profile/recent-doctors`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            if (data.success && data.doctors.length > 0) {
                let html = '';
                data.doctors.forEach(doctor => {
                    const avatarHtml = doctor.avatar_html || `<span>${doctor.name.charAt(0)}</span>`;
                    html += `
                        <div class="doctor-item">
                            <div class="doctor-avatar">
                                ${avatarHtml}
                            </div>
                            <div class="doctor-info">
                                <h4>${escapeHtml(doctor.name)}</h4>
                                <p>${escapeHtml(doctor.specialty || 'General')} • Fee: ${doctor.formatted_fee || 'N/A'}</p>
                            </div>
                        </div>
                    `;
                });
                doctorsList.innerHTML = html;
                viewAllLink.style.display = 'block';
            } else {
                doctorsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <p>No doctors added yet</p>
                        <a href="{{ route('hospital.doctors.create') }}" class="btn btn-primary btn-sm" style="margin-top: 0.5rem;">Add First Doctor</a>
                    </div>
                `;
                viewAllLink.style.display = 'none';
            }
            
            doctorsSkeleton.style.display = 'none';
            doctorsCard.style.display = 'block';
        } catch (error) {
            console.error('Error loading recent doctors:', error);
            doctorsList.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error loading doctors</p></div>';
            doctorsSkeleton.style.display = 'none';
            doctorsCard.style.display = 'block';
            viewAllLink.style.display = 'block';
        }
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
</script>
@endpush