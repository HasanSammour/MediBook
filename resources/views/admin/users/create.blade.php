{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New User')
@section('page-title', 'Add New User')
@section('page-subtitle', 'Create a new user account')

@push('styles')
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Tabs - Centered */
        .role-tabs {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray-color);
            transition: all 0.3s ease;
            border-radius: 30px;
        }

        .tab-btn:hover {
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.05);
        }

        .tab-btn.active {
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.1);
        }

        .tab-btn i {
            margin-right: 6px;
            font-size: 0.85rem;
        }

        /* Form Sections */
        .form-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .form-section.active {
            display: block;
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

        .form-title {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
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

        .form-group label .required {
            color: var(--danger-color);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Avatar Upload Section */
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
            flex-wrap: wrap;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-preview .default-icon {
            color: white;
            font-size: 2rem;
        }

        .upload-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: var(--primary-dark);
        }

        .upload-note {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-top: 0.5rem;
        }

        .remove-avatar-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            margin-left: 0.5rem;
        }

        .remove-avatar-btn:hover {
            background: #fecaca;
        }

        .avatar-status {
            font-size: 0.7rem;
            margin-top: 0.5rem;
        }

        .avatar-status.success {
            color: #10b981;
        }

        .avatar-status.error {
            color: #ef4444;
        }

        .info-note {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
            font-size: 0.8rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .btn-save {
            padding: 10px 24px;
        }

        .btn-cancel {
            padding: 10px 24px;
            background: #f3f4f6;
            color: var(--dark-color);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.8rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 1rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-actions {
                flex-direction: column;
            }

            .avatar-section {
                flex-direction: column;
                text-align: center;
            }

            .role-tabs {
                gap: 0.25rem;
            }

            .tab-btn {
                padding: 8px 16px;
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-container">
        <div class="form-card">
            <!-- Role Tabs -->
            <div class="role-tabs">
                <button type="button" class="tab-btn active" data-role="system_admin">
                    <i class="fas fa-crown"></i> System Admin
                </button>
                <button type="button" class="tab-btn" data-role="hospital_admin">
                    <i class="fas fa-hospital-user"></i> Hospital Admin
                </button>
                <button type="button" class="tab-btn" data-role="doctor">
                    <i class="fas fa-user-md"></i> Doctor
                </button>
                <button type="button" class="tab-btn" data-role="patient">
                    <i class="fas fa-user"></i> Patient
                </button>
            </div>

            <div id="errorMessage" class="alert alert-error" style="display: none;"></div>

            <!-- Avatar Upload Section (Common for all roles) -->
            <div class="avatar-section">
                <div class="avatar-preview" id="avatarPreview">
                    <div class="default-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <img id="avatarImg" src="#" alt="Preview" style="display: none;">
                </div>
                <div>
                    <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg"
                        style="display: none;" onchange="previewAvatar(this)">
                    <button type="button" class="upload-btn" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-upload"></i> Upload Avatar (Optional)
                    </button>
                    <button type="button" class="remove-avatar-btn" onclick="removeAvatar()" style="display: none;"
                        id="removeAvatarBtn">
                        <i class="fas fa-times"></i> Remove Avatar
                    </button>
                    <div class="upload-note">Recommended: Square image, max 2MB (JPG, PNG)</div>
                    <div id="avatarStatus" class="avatar-status"></div>
                </div>
            </div>

            <!-- Form for System Admin -->
            <div id="system_admin_section" class="form-section active">
                <h3 class="form-title">System Admin Information</h3>
                <form class="role-form" data-role="system_admin">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                </form>
            </div>

            <!-- Form for Hospital Admin -->
            <div id="hospital_admin_section" class="form-section">
                <h3 class="form-title">Hospital Admin Information</h3>
                <form class="role-form" data-role="hospital_admin">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth">
                        </div>
                        <div class="form-group">
                            <label>Hospital <span class="required">*</span></label>
                            <select name="hospital_id" required>
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Form for Doctor -->
            <div id="doctor_section" class="form-section">
                <h3 class="form-title">Doctor Information</h3>
                <form class="role-form" data-role="doctor">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth">
                        </div>
                        <div class="form-group">
                            <label>Hospital <span class="required">*</span></label>
                            <select name="hospital_id" required>
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Specialty <span class="required">*</span></label>
                            <select name="specialization" required>
                                <option value="">Select Specialty</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}">{{ $specialty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Consultation Fee ($) <span class="required">*</span></label>
                            <input type="number" name="consultation_fee" placeholder="Enter consultation fee" required>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Form for Patient -->
            <div id="patient_section" class="form-section">
                <h3 class="form-title">Patient Information</h3>
                <form class="role-form" data-role="patient">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                </form>
            </div>

            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Login credentials will be sent automatically to the user's email address after registration.</span>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-primary btn-save" id="submitBtn">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentUserId = null;
        let selectedRole = 'system_admin';

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));

                this.classList.add('active');
                selectedRole = this.getAttribute('data-role');
                document.getElementById(`${selectedRole}_section`).classList.add('active');
            });
        });

        // ============================================
        // AVATAR PREVIEW FUNCTION
        // ============================================

        function previewAvatar(input) {
            var previewDiv = document.getElementById('avatarPreview');
            var previewImg = document.getElementById('avatarImg');
            var defaultIcon = previewDiv.querySelector('.default-icon');
            var removeBtn = document.getElementById('removeAvatarBtn');
            var avatarStatus = document.getElementById('avatarStatus');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                var maxSize = 2 * 1024 * 1024; // 2MB

                // Validate file type
                if (validTypes.indexOf(file.type) === -1) {
                    if (avatarStatus) {
                        avatarStatus.innerHTML = '<span style="color: #ef4444;">Invalid file type. Please upload JPG, PNG, or GIF images only.</span>';
                        avatarStatus.style.display = 'block';
                    } else {
                        Swal.fire('Error', 'Invalid file type. Please upload JPG, PNG, or GIF images only.', 'error');
                    }
                    input.value = '';
                    return;
                }

                // Validate file size
                if (file.size > maxSize) {
                    if (avatarStatus) {
                        avatarStatus.innerHTML = '<span style="color: #ef4444;">File is too large. Maximum size is 2MB.</span>';
                        avatarStatus.style.display = 'block';
                    } else {
                        Swal.fire('Error', 'File is too large. Maximum size is 2MB.', 'error');
                    }
                    input.value = '';
                    return;
                }

                // Clear error
                if (avatarStatus) {
                    avatarStatus.innerHTML = '';
                    avatarStatus.style.display = 'none';
                }

                // Preview the image
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (defaultIcon) defaultIcon.style.display = 'none';
                    if (removeBtn) removeBtn.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        }

        // ============================================
        // REMOVE AVATAR FUNCTION (for create page)
        // ============================================

        function removeAvatar() {
            var fileInput = document.getElementById('avatarInput');
            var previewImg = document.getElementById('avatarImg');
            var defaultIcon = document.querySelector('#avatarPreview .default-icon');
            var removeBtn = document.getElementById('removeAvatarBtn');
            var avatarStatus = document.getElementById('avatarStatus');

            // Clear the file input
            fileInput.value = '';

            // Reset preview
            previewImg.src = '#';
            previewImg.style.display = 'none';
            if (defaultIcon) defaultIcon.style.display = 'flex';

            // Hide remove button
            if (removeBtn) removeBtn.style.display = 'none';

            // Clear status
            if (avatarStatus) {
                avatarStatus.innerHTML = '';
                avatarStatus.style.display = 'none';
            }
        }

        // ============================================
        // UPLOAD AVATAR FUNCTION
        // ============================================

        async function uploadAvatar(userId, file) {
            var formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');

            var avatarStatus = document.getElementById('avatarStatus');
            if (avatarStatus) {
                avatarStatus.innerHTML = '<span style="color: #f59e0b;">Uploading avatar...</span>';
                avatarStatus.style.display = 'block';
            }

            try {
                var response = await fetch('/admin/users/' + userId + '/upload-avatar', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    if (avatarStatus) {
                        avatarStatus.innerHTML = '<span style="color: #10b981;">✓ Avatar uploaded successfully!</span>';
                        setTimeout(function () {
                            avatarStatus.style.display = 'none';
                        }, 3000);
                    }
                } else {
                    if (avatarStatus) {
                        avatarStatus.innerHTML = '<span style="color: #ef4444;">✗ Failed to upload avatar.</span>';
                    }
                }
            } catch (error) {
                console.error('Error uploading avatar:', error);
                if (avatarStatus) {
                    avatarStatus.innerHTML = '<span style="color: #ef4444;">✗ Error uploading avatar.</span>';
                }
            }
        }

        // Get form data based on selected role
        function getFormData() {
            const activeForm = document.querySelector(`#${selectedRole}_section .role-form`);
            const formData = new FormData();

            formData.append('role', selectedRole);

            // Common fields
            const name = activeForm.querySelector('[name="name"]').value;
            const email = activeForm.querySelector('[name="email"]').value;
            const phone = activeForm.querySelector('[name="phone"]')?.value || '';
            const gender = activeForm.querySelector('[name="gender"]')?.value || '';
            const dateOfBirth = activeForm.querySelector('[name="date_of_birth"]')?.value || '';

            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('gender', gender);
            formData.append('date_of_birth', dateOfBirth);

            // Hospital ID (for hospital_admin and doctor)
            const hospitalId = activeForm.querySelector('[name="hospital_id"]');
            if (hospitalId) {
                formData.append('hospital_id', hospitalId.value);
            }

            // Doctor specific fields
            const specialization = activeForm.querySelector('[name="specialization"]');
            const consultationFee = activeForm.querySelector('[name="consultation_fee"]');
            if (specialization && consultationFee) {
                formData.append('specialization', specialization.value);
                formData.append('consultation_fee', consultationFee.value);
            }

            return formData;
        }

        // Validate form
        function validateForm() {
            const activeForm = document.querySelector(`#${selectedRole}_section .role-form`);
            const name = activeForm.querySelector('[name="name"]').value;
            const email = activeForm.querySelector('[name="email"]').value;

            if (!name || !email) {
                Swal.fire('Error', 'Please fill in all required fields.', 'error');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire('Error', 'Please enter a valid email address.', 'error');
                return false;
            }

            // Validate hospital for hospital_admin and doctor
            if (selectedRole === 'hospital_admin' || selectedRole === 'doctor') {
                const hospitalId = activeForm.querySelector('[name="hospital_id"]').value;
                if (!hospitalId) {
                    Swal.fire('Error', 'Please select a hospital.', 'error');
                    return false;
                }
            }

            // Validate doctor specific fields
            if (selectedRole === 'doctor') {
                const specialization = activeForm.querySelector('[name="specialization"]').value;
                const consultationFee = activeForm.querySelector('[name="consultation_fee"]').value;
                if (!specialization || !consultationFee) {
                    Swal.fire('Error', 'Please fill in all doctor information fields.', 'error');
                    return false;
                }
                if (consultationFee <= 0) {
                    Swal.fire('Error', 'Consultation fee must be greater than 0.', 'error');
                    return false;
                }
            }

            return true;
        }

        // Submit Form
        document.getElementById('submitBtn').addEventListener('click', async function () {
            if (!validateForm()) return;

            var submitBtn = this;
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

            var errorDiv = document.getElementById('errorMessage');
            errorDiv.style.display = 'none';

            var formData = getFormData();

            try {
                var response = await fetch('{{ route("admin.users.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    currentUserId = data.user_id;

                    // Upload avatar if selected
                    var avatarFile = document.getElementById('avatarInput').files[0];
                    if (avatarFile) {
                        await uploadAvatar(currentUserId, avatarFile);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'User Created!',
                        text: data.message,
                        confirmButtonColor: '#2563eb'
                    }).then(function () {
                        window.location.href = data.redirect;
                    });
                } else {
                    var errorMsg = '';
                    if (typeof data.message === 'object') {
                        errorMsg = Object.values(data.message).flat().join('<br>');
                    } else {
                        errorMsg = data.message || 'Please check all fields and try again.';
                    }
                    errorDiv.innerHTML = errorMsg;
                    errorDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error:', error);
                errorDiv.innerHTML = 'Network error. Please try again.';
                errorDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
@endpush