{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your profile and system preferences')

@push('styles')
    <style>
        .settings-wrapper {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
        }

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

        .settings-content {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
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
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Profile Image */
        .profile-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.85rem;
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
            cursor: pointer;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.8rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
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
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .profile-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="settings-wrapper">
        <!-- Sidebar -->
        <div class="settings-sidebar">
            <ul class="settings-menu">
                <li><a href="#" class="active" data-section="profile"><i class="fas fa-user-circle"></i> Admin Profile</a>
                </li>
                <li><a href="#" data-section="general"><i class="fas fa-globe"></i> General Settings</a></li>
                <li><a href="#" data-section="notifications"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="#" data-section="security"><i class="fas fa-shield-alt"></i> Security</a></li>
                <li><a href="#" data-section="maintenance"><i class="fas fa-tools"></i> Maintenance</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="settings-content">
            <!-- Profile Section (Working) -->
            <div id="profileSection" class="settings-section active">
                <h2 class="section-title">Admin Profile</h2>
                <p class="section-subtitle">Update your personal information and password</p>

                <div id="profileSuccess" class="alert alert-success" style="display: none;"></div>
                <div id="profileError" class="alert alert-error" style="display: none;"></div>

                <div class="profile-section">
                    <div class="profile-avatar" id="avatarPreview">
                        {!! $admin->avatar_html !!}
                    </div>
                    <div>
                        <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/jpg" style="display: none;">
                        <button type="button" class="upload-btn" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-upload"></i> Change Avatar
                        </button>
                        <p class="upload-note" style="font-size: 0.65rem; color: #6b7280; margin-top: 0.5rem;">JPG, PNG or
                            GIF. Max 2MB</p>
                    </div>
                </div>

                <form id="profileForm">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ $admin->name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" value="{{ $admin->email }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="{{ $admin->phone }}">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $admin->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $admin->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $admin->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth"
                            value="{{ $admin->date_of_birth ? $admin->date_of_birth->format('Y-m-d') : '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>

                <!-- Password Change -->
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #f0f0f0;">
                    <h3 style="font-size: 1rem; margin-bottom: 1rem;">Change Password</h3>
                    <div id="passwordSuccess" class="alert alert-success" style="display: none;"></div>
                    <div id="passwordError" class="alert alert-error" style="display: none;"></div>
                    <form id="passwordForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Current Password <span class="required">*</span></label>
                            <input type="password" name="current_password" placeholder="Enter current password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password <span class="required">*</span></label>
                            <input type="password" name="password" id="newPassword" placeholder="Enter new password"
                                required>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- General Settings (Template Only) -->
            <div id="generalSection" class="settings-section">
                <h2 class="section-title">General Settings</h2>
                <p class="section-subtitle">Configure basic platform settings</p>

                <div class="form-group">
                    <label>Platform Name</label>
                    <input type="text" value="MediBook" disabled style="background: #f9fafb;">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Platform Email</label>
                        <input type="email" value="info@medibook.com" disabled style="background: #f9fafb;">
                    </div>
                    <div class="form-group">
                        <label>Support Email</label>
                        <input type="email" value="support@medibook.com" disabled style="background: #f9fafb;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" value="+1 (234) 567-890" disabled style="background: #f9fafb;">
                    </div>
                    <div class="form-group">
                        <label>Timezone</label>
                        <select disabled style="background: #f9fafb;">
                            <option>Eastern Time (ET)</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary btn-save" onclick="comingSoon()">Save Changes</button>
            </div>

            <!-- Notifications (Template Only) -->
            <div id="notificationsSection" class="settings-section">
                <h2 class="section-title">Notification Settings</h2>
                <p class="section-subtitle">Configure email and system notifications</p>

                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Email Notifications</h4>
                        <p>Send email notifications for system events</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Appointment Reminders</h4>
                        <p>Send appointment reminders to patients</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>New Registration Alerts</h4>
                        <p>Notify admins when new hospitals register</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <button class="btn btn-primary btn-save" onclick="comingSoon()">Save Preferences</button>
            </div>

            <!-- Security (Template Only) -->
            <div id="securitySection" class="settings-section">
                <h2 class="section-title">Security Settings</h2>
                <p class="section-subtitle">Manage security and authentication settings</p>

                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Two-Factor Authentication</h4>
                        <p>Require 2FA for admin accounts</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Session Timeout</h4>
                        <p>Auto-logout after period of inactivity</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label>Session Timeout (minutes)</label>
                    <select disabled style="background: #f9fafb;">
                        <option>30 minutes</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-save" onclick="comingSoon()">Save Security Settings</button>

                <div class="danger-zone">
                    <h4>Danger Zone</h4>
                    <p>Clear all system logs and cached data. This action cannot be undone.</p>
                    <button type="button" class="btn-danger" onclick="comingSoon()">Clear System Data</button>
                </div>
            </div>

            <!-- Maintenance (Template Only) -->
            <div id="maintenanceSection" class="settings-section">
                <h2 class="section-title">Maintenance</h2>
                <p class="section-subtitle">System maintenance and backup settings</p>

                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Maintenance Mode</h4>
                        <p>Put the platform in maintenance mode</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label>Maintenance Message</label>
                    <textarea rows="3" disabled
                        style="background: #f9fafb; width: 100%; padding: 10px; border-radius: 12px; border: 1px solid #e5e7eb;">We are currently performing scheduled maintenance. Please check back soon.</textarea>
                </div>
                <div class="form-group">
                    <label>Auto Backup Schedule</label>
                    <select disabled style="background: #f9fafb; width: 100%; padding: 10px; border-radius: 12px;">
                        <option>Weekly</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-save" onclick="comingSoon()">Save Maintenance Settings</button>

                <div class="danger-zone">
                    <h4>System Actions</h4>
                    <p>Create a manual backup of the system database</p>
                    <button type="button" class="btn-success"
                        style="background: #10b981; color: white; border: none; padding: 8px 20px; border-radius: 10px; cursor: pointer;"
                        onclick="comingSoon()">Create Backup Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tab switching
        document.querySelectorAll('.settings-menu a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.settings-menu a').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(`${this.getAttribute('data-section')}Section`).classList.add('active');
            });
        });

        // Coming Soon function
        function comingSoon() {
            Swal.fire({
                title: 'Coming Soon',
                text: 'This feature will be available in the next update.',
                icon: 'info',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Got it'
            });
        }

        // Avatar upload
        document.getElementById('avatarInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', '{{ csrf_token() }}');

                Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch('{{ route("admin.settings.avatar") }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('avatarPreview').innerHTML = data.avatar_html;
                            Swal.fire('Success!', data.message, 'success');
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Failed to upload avatar.', 'error'));
            }
        });

        // Password strength checker
        document.getElementById('newPassword')?.addEventListener('input', function () {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            if (!password) { strengthDiv.innerHTML = ''; return; }
            let strength = 'weak', message = '';
            if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
                strength = 'strong'; message = 'Strong password ✓';
            } else if (password.length >= 6 && (/[A-Z]/.test(password) || /[0-9]/.test(password))) {
                strength = 'medium'; message = 'Medium password';
            } else {
                strength = 'weak'; message = 'Weak password';
            }
            strengthDiv.innerHTML = `<span class="strength-${strength}">${message}</span>`;
        });

        // Profile form submit
        document.getElementById('profileForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const formData = new FormData(this);
            formData.set('_method', 'PUT');

            try {
                const response = await fetch('{{ route("admin.settings.profile") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('profileSuccess').style.display = 'block';
                    document.getElementById('profileSuccess').innerHTML = data.message;
                    setTimeout(() => document.getElementById('profileSuccess').style.display = 'none', 3000);
                } else {
                    let msg = typeof data.message === 'object' ? Object.values(data.message).flat().join('<br>') : data.message;
                    document.getElementById('profileError').style.display = 'block';
                    document.getElementById('profileError').innerHTML = msg;
                    setTimeout(() => document.getElementById('profileError').style.display = 'none', 3000);
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Password form submit
        document.getElementById('passwordForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            const formData = new FormData(this);
            formData.set('_method', 'PUT');

            try {
                const response = await fetch('{{ route("admin.settings.password") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('passwordSuccess').style.display = 'block';
                    document.getElementById('passwordSuccess').innerHTML = data.message;
                    this.reset();
                    setTimeout(() => document.getElementById('passwordSuccess').style.display = 'none', 3000);
                } else {
                    let msg = typeof data.message === 'object' ? Object.values(data.message).flat().join('<br>') : data.message;
                    document.getElementById('passwordError').style.display = 'block';
                    document.getElementById('passwordError').innerHTML = msg;
                    setTimeout(() => document.getElementById('passwordError').style.display = 'none', 3000);
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
@endpush