@extends('layouts.patient')

@section('title', 'Profile Settings')

@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your account information and preferences')

@push('styles')
    <style>
        /* Settings Layout */
        .settings-wrapper {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }

        /* Settings Sidebar */
        .settings-sidebar {
            background: var(--white);
            border-radius: 20px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            height: fit-content;
        }

        .settings-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .settings-menu li {
            margin-bottom: 0.25rem;
        }

        .settings-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: var(--gray-color);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .settings-menu a i {
            width: 20px;
            font-size: 1rem;
        }

        .settings-menu a:hover {
            background: rgba(37, 99, 235, 0.05);
            color: var(--primary-color);
        }

        .settings-menu a.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        /* Settings Content */
        .settings-content {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .settings-section {
            display: none;
        }

        .settings-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Profile Image Section */
        .profile-image-section {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar svg {
            width: 100%;
            height: 100%;
        }

        .upload-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: var(--primary-dark);
        }

        .upload-note {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-top: 0.5rem;
        }

        /* Form Styles */
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

        .form-group label .required {
            color: var(--danger-color);
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

        .form-group input:disabled {
            background: #f9fafb;
            cursor: not-allowed;
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

        /* Age Display */
        .age-display {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .age-display i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .age-display span {
            font-size: 0.9rem;
            color: var(--dark-color);
        }

        .age-display strong {
            color: var(--primary-color);
            font-size: 1rem;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.7rem;
        }

        .strength-weak {
            color: #ef4444;
        }

        .strength-medium {
            color: #f59e0b;
        }

        .strength-strong {
            color: #10b981;
        }

        /* Toggle Switch */
        .toggle-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .toggle-option:last-child {
            border-bottom: none;
        }

        .toggle-info h4 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .toggle-info p {
            font-size: 0.7rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: var(--primary-color);
        }

        input:checked+.toggle-slider:before {
            transform: translateX(26px);
        }

        /* Info Box */
        .info-box {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
        }

        .info-box i {
            color: #2563eb;
            margin-right: 0.5rem;
        }

        .info-box span {
            font-size: 0.8rem;
            color: #1f2937;
        }

        .info-box ul {
            margin-top: 0.5rem;
            margin-left: 1.5rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Danger Zone */
        .danger-zone {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #fee2e2;
        }

        .danger-zone h4 {
            color: var(--danger-color);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .danger-zone p {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin-bottom: 1rem;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .settings-wrapper {
                grid-template-columns: 1fr;
            }

            .settings-sidebar {
                position: static;
                width: 100%;
            }

            .settings-menu {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .settings-menu li {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .settings-content {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .profile-image-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="settings-wrapper">
        <!-- Settings Sidebar -->
        <div class="settings-sidebar">
            <ul class="settings-menu">
                <li><a href="#" class="active" data-section="personal"><i class="fas fa-user"></i> Personal Information</a>
                </li>
                <li><a href="#" data-section="security"><i class="fas fa-lock"></i> Account Security</a></li>
                <li><a href="#" data-section="notifications"><i class="fas fa-bell"></i> Notifications</a></li>
            </ul>
        </div>

        <!-- Settings Content -->
        <div class="settings-content">
            <!-- Personal Information Section -->
            <div id="personalSection" class="settings-section active">
                <h2 class="section-title">Personal Information</h2>
                <p class="section-subtitle">Update your personal details and profile information</p>

                <form id="personalForm">
                    @csrf
                    @method('PUT')

                    <div class="profile-image-section">
                        <div class="profile-avatar" id="profileAvatar">
                            {!! $user->avatar_html !!}
                        </div>
                        <div>
                            <button type="button" class="upload-btn" id="uploadPhotoBtn">
                                <i class="fas fa-camera"></i> Change Photo
                            </button>
                            <div class="upload-note">JPG, PNG or GIF. Max 2MB</div>
                            <input type="file" id="photoInput" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" id="fullName" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" id="email" value="{{ $user->email }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" id="phone" value="{{ $user->phone ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select id="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" id="dateOfBirth"
                                value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                        <div class="form-group">
                            <label>Member Since</label>
                            <input type="text" value="{{ $user->created_at->format('F d, Y') }}" disabled
                                style="background: #f9fafb;">
                        </div>
                    </div>

                    <!-- Age Display (Auto-calculated from DOB) -->
                    <div class="age-display" id="ageDisplay">
                        <i class="fas fa-birthday-cake"></i>
                        <span>Your Age: <strong id="ageValue">{{ $age ?? 'Not specified' }}</strong></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>

            <!-- Account Security Section -->
            <div id="securitySection" class="settings-section">
                <h2 class="section-title">Account Security</h2>
                <p class="section-subtitle">Change your password and manage security settings</p>

                <form id="passwordForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Current Password <span class="required">*</span></label>
                        <input type="password" id="currentPassword" placeholder="Enter your current password" required>
                    </div>

                    <div class="form-group">
                        <label>New Password <span class="required">*</span></label>
                        <input type="password" id="newPassword" placeholder="Enter new password" required>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password <span class="required">*</span></label>
                        <input type="password" id="confirmPassword" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>

                <div class="danger-zone">
                    <h4>Danger Zone</h4>
                    <p>Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" class="btn-danger" id="deleteAccountBtn">Delete Account</button>
                </div>
            </div>

            <!-- Notifications Section -->
            <div id="notificationsSection" class="settings-section">
                <h2 class="section-title">Notification Preferences</h2>
                <p class="section-subtitle">Choose what notifications you want to receive</p>

                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Email Notifications</h4>
                        <p>Receive appointment confirmations, reminders, and account updates via email</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="emailNotifications" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Health Tips Newsletter</h4>
                        <p>Receive weekly health tips and medical news</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="newsletter">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <span>You will receive the following notifications:</span>
                    <ul>
                        <li>Appointment confirmation after booking</li>
                        <li>Email change notifications</li>
                        <li>Account deletion confirmation</li>
                        <li>Weekly health tips (if subscribed)</li>
                    </ul>
                </div>

                <button class="btn btn-primary btn-save" id="saveNotificationsBtn" style="margin-top: 1rem;">
                    <i class="fas fa-save"></i> Save Preferences
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Section Navigation
            document.querySelectorAll('.settings-menu a').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelectorAll('.settings-menu a').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(`${this.getAttribute('data-section')}Section`).classList.add('active');
                });
            });

            // Calculate Age from Date of Birth
            function calculateAge(dateOfBirth) {
                if (!dateOfBirth) return null;
                const today = new Date();
                const birthDate = new Date(dateOfBirth);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            // Update age display when DOB changes
            const dobInput = document.getElementById('dateOfBirth');
            const ageValueSpan = document.getElementById('ageValue');
            const ageDisplayDiv = document.getElementById('ageDisplay');

            function updateAgeDisplay() {
                const dob = dobInput.value;
                if (dob) {
                    const age = calculateAge(dob);
                    if (age !== null) {
                        ageValueSpan.textContent = age + ' years';
                        ageDisplayDiv.style.display = 'flex';
                    } else {
                        ageValueSpan.textContent = 'Invalid date';
                    }
                } else {
                    ageValueSpan.textContent = 'Not specified';
                }
            }

            dobInput?.addEventListener('change', updateAgeDisplay);
            dobInput?.addEventListener('input', updateAgeDisplay);

            // Password Strength Checker
            const passwordInput = document.getElementById('newPassword');
            const strengthDiv = document.getElementById('passwordStrength');

            passwordInput?.addEventListener('input', function () {
                const password = this.value;
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

            // Upload Photo
            const uploadBtn = document.getElementById('uploadPhotoBtn');
            const photoInput = document.getElementById('photoInput');

            uploadBtn?.addEventListener('click', () => photoInput.click());

            photoInput?.addEventListener('change', async function (e) {
                if (!this.files || !this.files[0]) return;

                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload JPG, PNG, or GIF images only.',
                        confirmButtonColor: '#2563eb'
                    });
                    this.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size must be less than 2MB.',
                        confirmButtonColor: '#2563eb'
                    });
                    this.value = '';
                    return;
                }

                const formData = new FormData();
                formData.append('photo', file);

                Swal.fire({
                    title: 'Uploading...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await fetch('{{ route("patient.profile.photo") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        const avatarDiv = document.getElementById('profileAvatar');
                        avatarDiv.innerHTML = `<img src="${data.photo_url}?t=${Date.now()}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">`;
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please check your connection and try again.',
                        confirmButtonColor: '#2563eb'
                    });
                }
                this.value = '';
            });

            // Personal Form Submit (includes gender and DOB)
            document.getElementById('personalForm')?.addEventListener('submit', async function (e) {
                e.preventDefault();

                const fullName = document.getElementById('fullName').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const gender = document.getElementById('gender').value;
                const dateOfBirth = document.getElementById('dateOfBirth').value;

                if (!fullName) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter your full name.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter your email address.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await fetch('{{ route("patient.profile.update") }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: fullName,
                            email: email,
                            phone: phone,
                            gender: gender,
                            date_of_birth: dateOfBirth
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                } catch (error) {
                    console.error('Update error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please check your connection and try again.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });

            // Password Form Submit
            document.getElementById('passwordForm')?.addEventListener('submit', async function (e) {
                e.preventDefault();

                const currentPassword = document.getElementById('currentPassword').value;
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (!currentPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter your current password.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                if (!newPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter a new password.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                if (newPassword.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Too Short',
                        text: 'Password must be at least 6 characters.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Do Not Match',
                        text: 'Please make sure your passwords match.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await fetch('{{ route("patient.profile.password") }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: currentPassword,
                            password: newPassword,
                            password_confirmation: confirmPassword
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        document.getElementById('passwordForm').reset();
                        document.getElementById('passwordStrength').innerHTML = '';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                } catch (error) {
                    console.error('Password error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please check your connection and try again.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });

            // Save Notifications
            document.getElementById('saveNotificationsBtn')?.addEventListener('click', async function () {
                const emailNotifications = document.getElementById('emailNotifications').checked;
                const newsletter = document.getElementById('newsletter').checked;

                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                // Simulate API call (replace with actual when backend ready)
                await new Promise(resolve => setTimeout(resolve, 800));

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Notification preferences saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // Delete Account
            document.getElementById('deleteAccountBtn')?.addEventListener('click', async () => {
                Swal.fire({
                    title: 'Delete Account?',
                    text: 'This action cannot be undone. Your account will be deactivated.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, delete my account',
                    cancelButtonText: 'Cancel',
                    input: 'password',
                    inputPlaceholder: 'Enter your password to confirm',
                    inputAttributes: { autocapitalize: 'off' },
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('Please enter your password');
                        }
                        return password;
                    }
                }).then(async (result) => {
                    if (result.isConfirmed && result.value) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        try {
                            const response = await fetch('{{ route("patient.profile.destroy") }}', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ password: result.value })
                            });

                            const data = await response.json();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Account Deleted',
                                    text: data.message,
                                    confirmButtonColor: '#2563eb'
                                }).then(() => {
                                    window.location.href = '{{ route("home") }}';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed',
                                    text: data.message,
                                    confirmButtonColor: '#2563eb'
                                });
                            }
                        } catch (error) {
                            console.error('Delete error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Network Error',
                                text: 'Please check your connection and try again.',
                                confirmButtonColor: '#2563eb'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush