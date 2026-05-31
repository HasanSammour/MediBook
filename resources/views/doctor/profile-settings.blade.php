@extends('layouts.doctor')

@section('title', 'Profile Settings')

@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your account and professional information')

@push('styles')
<style>
    .settings-wrapper {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 1.5rem;
    }

    .profile-sidebar {
        background: var(--white);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.75rem;
        cursor: pointer;
        margin-top: 0.5rem;
        width: 100%;
    }

    .upload-btn:hover {
        background: var(--primary-dark);
    }

    .profile-name {
        font-size: 1.2rem;
        margin-top: 1rem;
        margin-bottom: 0.25rem;
    }

    .profile-hospital {
        color: var(--gray-color);
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .profile-hospital i {
        color: var(--primary-color);
        margin-right: 4px;
    }

    .profile-specialty {
        color: var(--primary-color);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        padding: 1rem 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        margin: 1rem 0;
    }

    .profile-stat {
        text-align: center;
    }

    .profile-stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    .profile-stat-label {
        font-size: 0.6rem;
        color: var(--gray-color);
    }

    .settings-tabs {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 1px solid #f0f0f0;
        overflow-x: auto;
    }

    .tab-btn {
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--gray-color);
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .tab-btn:hover {
        color: var(--primary-color);
    }

    .tab-btn.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
    }

    .tab-content {
        padding: 1.5rem;
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--dark-color);
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn-save {
        padding: 10px 24px;
        margin-top: 0.5rem;
    }

    .availability-display {
        background: #f9fafb;
        border-radius: 16px;
        padding: 1rem;
    }

    .availability-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .availability-row:last-child {
        border-bottom: none;
    }

    .availability-day {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dark-color);
    }

    .availability-hours {
        font-size: 0.8rem;
        color: var(--gray-color);
    }

    .availability-hours.off {
        color: #ef4444;
    }

    .edit-schedule-link {
        margin-top: 1rem;
        text-align: center;
    }

    .edit-schedule-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.8rem;
    }

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.65rem;
    }

    .strength-weak { color: #ef4444; }
    .strength-medium { color: #f59e0b; }
    .strength-strong { color: #10b981; }

    .danger-zone {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #fee2e2;
    }

    .danger-zone h4 {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .danger-zone p {
        font-size: 0.7rem;
        color: var(--gray-color);
        margin-bottom: 1rem;
    }

    .btn-danger {
        background: var(--danger-color);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        cursor: pointer;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    @media (max-width: 968px) {
        .settings-wrapper {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .tabs-header {
            padding: 0 0.5rem;
        }

        .tab-btn {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
        }

        .tab-content {
            padding: 1rem;
        }

        .availability-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-wrapper">
    <!-- Profile Sidebar -->
    <div class="profile-sidebar">
        <div class="profile-avatar" id="profileAvatar">
            {!! $doctor->avatar_html !!}
        </div>
        <input type="file" id="photoInput" accept="image/*" style="display: none;">
        <button class="upload-btn" onclick="document.getElementById('photoInput').click()">
            <i class="fas fa-camera"></i> Change Photo
        </button>
        <h3 class="profile-name">{{ $doctor->name }}</h3>
        <div class="profile-hospital">
            <i class="fas fa-hospital"></i> {{ $hospitalName }}
        </div>
        <p class="profile-specialty">{{ $doctor->specialization ?? 'General Physician' }}</p>
        
        <div class="profile-stats">
            <div class="profile-stat">
                <div class="profile-stat-value">{{ $doctor->created_at ? floor($doctor->created_at->diffInYears(now())) + 5 : 5 }}+</div>
                <div class="profile-stat-label">Years Exp</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-value">{{ $doctor->doctorAppointments()->where('status', 'completed')->count() }}+</div>
                <div class="profile-stat-label">Patients</div>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="personal">Personal Info</button>
            <button class="tab-btn" data-tab="professional">Professional</button>
            <button class="tab-btn" data-tab="availability">My Schedule</button>
            <button class="tab-btn" data-tab="security">Security</button>
        </div>

        <!-- Personal Info Tab (includes Gender & DOB) -->
        <div id="personalTab" class="tab-content active">
            <form id="personalForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" id="fullName" name="name" value="{{ $doctor->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ $doctor->email }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ $doctor->phone }}">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $doctor->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="date_of_birth" value="{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-save">Save Changes</button>
            </form>
        </div>

        <!-- Professional Tab -->
        <div id="professionalTab" class="tab-content">
            <form id="professionalForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Specialty</label>
                        <select id="specialty" name="specialization">
                            <option value="">Select Specialty</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ $doctor->specialization == $specialty ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Consultation Fee ($)</label>
                        <input type="number" id="consultationFee" name="consultation_fee" value="{{ $doctor->consultation_fee }}" step="5" min="0" max="200">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-save">Save Changes</button>
            </form>
        </div>

        <!-- Availability Tab -->
        <div id="availabilityTab" class="tab-content">
            <div class="availability-display">
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                    // Use working_hours instead of availability
                    $workingHours = $doctor->working_hours;

                    // If it's a string, decode it
                    if (is_string($workingHours)) {
                        $workingHours = json_decode($workingHours, true);
                    }
                    // If null or empty, set as empty array
                    if (!$workingHours) {
                        $workingHours = [];
                    }
                @endphp

                @foreach($days as $index => $day)
                    @php
                        $dayData = isset($workingHours[$day]) ? $workingHours[$day] : [];
                        $isWorking = isset($dayData['enabled']) && $dayData['enabled'] === true;

                        if ($isWorking && isset($dayData['start']) && isset($dayData['end'])) {
                            $start = $dayData['start'];
                            $end = $dayData['end'];
                            // Format times to 12-hour format
                            $startDisplay = date("g:i A", strtotime($start));
                            $endDisplay = date("g:i A", strtotime($end));
                            $hours = $startDisplay . ' - ' . $endDisplay;
                        } else {
                            $hours = 'Not working';
                        }
                    @endphp
                    <div class="availability-row">
                        <div class="availability-day">{{ $dayNames[$index] }}</div>
                        <div class="availability-hours {{ !$isWorking ? 'off' : '' }}">
                            @if($isWorking)
                                <i class="fas fa-clock"></i> {{ $hours }}
                            @else
                                <i class="fas fa-ban"></i> Day Off
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="edit-schedule-link">
                    <a href="{{ route('doctor.schedule') }}">
                        <i class="fas fa-edit"></i> Edit Working Hours
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="securityTab" class="tab-content">
            <form id="passwordForm">
                <div class="form-group">
                    <label>Current Password <span class="required">*</span></label>
                    <input type="password" id="currentPassword" name="current_password" placeholder="Enter current password" required>
                </div>
                <div class="form-group">
                    <label>New Password <span class="required">*</span></label>
                    <input type="password" id="newPassword" name="password" placeholder="Enter new password" required>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                <div class="form-group">
                    <label>Confirm New Password <span class="required">*</span></label>
                    <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-save">Update Password</button>
            </form>

            <div class="danger-zone">
                <h4>Danger Zone</h4>
                <p>Once you deactivate your account, you will not be visible to patients.</p>
                <button type="button" class="btn-danger" onclick="deactivateAccount()">Deactivate Account</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Password strength checker
document.getElementById('newPassword')?.addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthDiv.innerHTML = '';
        return;
    }
    
    let strength = 'weak';
    let message = '';
    
    if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
        strength = 'strong';
        message = 'Strong password ✓';
    } else if (password.length >= 6 && (/[A-Z]/.test(password) || /[0-9]/.test(password))) {
        strength = 'medium';
        message = 'Medium password';
    } else {
        strength = 'weak';
        message = 'Weak password';
    }
    
    strengthDiv.innerHTML = `<span class="strength-${strength}">${message}</span>`;
});

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(`${this.getAttribute('data-tab')}Tab`).classList.add('active');
    });
});

// Photo upload
document.getElementById('photoInput')?.addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    
    const formData = new FormData();
    formData.append('photo', file);
    
    try {
        const response = await fetch('{{ route("doctor.profile.photo") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            Swal.fire('Success!', 'Profile photo updated.', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Something went wrong.', 'error');
    }
});

// Personal form submit (includes name, email, phone, gender, DOB)
document.getElementById('personalForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const data = {
        name: document.getElementById('fullName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        gender: document.getElementById('gender').value,
        date_of_birth: document.getElementById('dateOfBirth').value,
    };
    
    try {
        const response = await fetch('{{ route("doctor.profile.personal") }}', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            Swal.fire('Success!', result.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            let errorMessage = 'Something went wrong.';
            if (typeof result.message === 'object') {
                errorMessage = Object.values(result.message).flat().join(', ');
            } else if (result.message) {
                errorMessage = result.message;
            }
            Swal.fire('Error', errorMessage, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Something went wrong.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Professional form submit (specialty and fee only)
document.getElementById('professionalForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const data = {
        specialization: document.getElementById('specialty').value,
        consultation_fee: document.getElementById('consultationFee').value,
    };
    
    try {
        const response = await fetch('{{ route("doctor.profile.professional") }}', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            Swal.fire('Success!', result.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            let errorMessage = 'Something went wrong.';
            if (typeof result.message === 'object') {
                errorMessage = Object.values(result.message).flat().join(', ');
            } else if (result.message) {
                errorMessage = result.message;
            }
            Swal.fire('Error', errorMessage, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Something went wrong.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Password form submit
document.getElementById('passwordForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        Swal.fire('Error', 'Passwords do not match.', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        Swal.fire('Error', 'Password must be at least 6 characters.', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';
    
    const data = {
        current_password: document.getElementById('currentPassword').value,
        password: newPassword,
        password_confirmation: confirmPassword
    };
    
    try {
        const response = await fetch('{{ route("doctor.profile.password") }}', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            Swal.fire('Success!', result.message, 'success');
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordStrength').innerHTML = '';
        } else {
            let errorMessage = 'Something went wrong.';
            if (typeof result.message === 'object') {
                errorMessage = Object.values(result.message).flat().join(', ');
            } else if (result.message) {
                errorMessage = result.message;
            }
            Swal.fire('Error', errorMessage, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Something went wrong.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Deactivate account
function deactivateAccount() {
    Swal.fire({
        title: 'Deactivate Account?',
        text: 'Are you sure you want to deactivate your account? You will not be visible to patients.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, deactivate',
        cancelButtonText: 'Cancel',
        input: 'password',
        inputPlaceholder: 'Enter your password to confirm',
        inputAttributes: { autocomplete: 'current-password' }
    }).then(async (result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            
            try {
                const response = await fetch('{{ route("doctor.profile.destroy") }}', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ password: result.value })
                });
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire('Deactivated', 'Your account has been deactivated.', 'success').then(() => {
                        window.location.href = '{{ route("home") }}';
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        }
    });
}
</script>
@endpush