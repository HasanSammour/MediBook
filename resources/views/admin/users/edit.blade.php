{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information')

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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Avatar Upload */
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
        }

        .avatar-preview img {
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

        .status-section {
            background: #f9fafb;
            border-radius: 16px;
            padding: 1rem;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .status-select {
            width: auto;
            min-width: 120px;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
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
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .avatar-section {
                flex-direction: column;
                text-align: center;
            }

            .status-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .status-select {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title">Edit {{ ucfirst(str_replace('_', ' ', $role)) }}</h2>

            <div id="errorMessage" class="alert alert-error" style="display: none;"></div>

            <!-- Avatar Upload -->
            <div class="avatar-section">
                <div class="avatar-preview" id="avatarPreview">
                    {!! $user->avatar_html !!}
                </div>
                <div>
                    <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg"
                        style="display: none;" onchange="previewAvatar(this)">
                    <button type="button" class="upload-btn" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-upload"></i> Change Avatar
                    </button>
                    <button type="button" class="remove-avatar-btn" onclick="removeAvatar()" style="display: none;"
                        id="removeAvatarBtn">
                        <i class="fas fa-times"></i> Remove Avatar
                    </button>
                    <div id="avatarStatus" class="avatar-status"></div>
                </div>
            </div>

            <form id="editUserForm">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ $user->email }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="{{ $user->phone }}">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
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
                        <input type="date" name="date_of_birth"
                            value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
                    </div>

                    @if($role === 'hospital_admin' || $role === 'doctor')
                        <div class="form-group">
                            <label>Hospital <span class="required">*</span></label>
                            <select name="hospital_id" required>
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}" {{ $user->hospital_id == $hospital->id ? 'selected' : '' }}>
                                        {{ $hospital->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                @if($role === 'doctor')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Specialty <span class="required">*</span></label>
                            <select name="specialization" required>
                                <option value="">Select Specialty</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}" {{ $user->specialization == $specialty ? 'selected' : '' }}>
                                        {{ $specialty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Consultation Fee ($) <span class="required">*</span></label>
                            <input type="number" name="consultation_fee" value="{{ $user->consultation_fee }}" required>
                        </div>
                    </div>
                @endif

                <!-- Status Section -->
                <div class="status-section">
                    <label>Account Status:</label>
                    <select name="is_active" class="status-select">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var userId = {{ $user->id }};
        var userAvatar = '{{ $user->profile_image }}';

        // ============================================
        // AVATAR PREVIEW FUNCTION
        // ============================================

        function previewAvatar(input) {
            var previewDiv = document.getElementById('avatarPreview');
            var removeBtn = document.getElementById('removeAvatarBtn');
            var avatarStatus = document.getElementById('avatarStatus');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                var maxSize = 2 * 1024 * 1024;

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
                    previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
                    if (removeBtn) removeBtn.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        }

        // ============================================
        // REMOVE AVATAR FUNCTION
        // ============================================

        function removeAvatar() {
            var fileInput = document.getElementById('avatarInput');
            var previewDiv = document.getElementById('avatarPreview');
            var removeBtn = document.getElementById('removeAvatarBtn');
            var avatarStatus = document.getElementById('avatarStatus');
            var form = document.getElementById('editUserForm');

            // Clear the file input
            fileInput.value = '';

            // Add hidden input to mark that avatar should be removed
            var removeFlag = document.getElementById('remove_avatar_flag');
            if (!removeFlag) {
                removeFlag = document.createElement('input');
                removeFlag.type = 'hidden';
                removeFlag.name = 'remove_avatar';
                removeFlag.id = 'remove_avatar_flag';
                removeFlag.value = '1';
                form.appendChild(removeFlag);
            } else {
                removeFlag.value = '1';
            }

            // Reset preview to default avatar with user initial
            var userName = document.querySelector('input[name="name"]')?.value || 'User';
            var initial = userName.charAt(0);
            previewDiv.innerHTML = '<div class="default-avatar" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2563eb, #1d4ed8); border-radius: 50%;"><span style="color: white; font-size: 1.5rem; font-weight: 600;">' + initial + '</span></div>';

            // Hide remove button
            if (removeBtn) removeBtn.style.display = 'none';

            // Clear status
            if (avatarStatus) {
                avatarStatus.innerHTML = '';
                avatarStatus.style.display = 'none';
            }
        }

        // ============================================
        // SHOW/HIDE REMOVE BUTTON ON PAGE LOAD
        // ============================================

        function shouldShowRemoveButton() {
            // If avatar is from seeded images folder, hide remove button
            if (userAvatar && (userAvatar.indexOf('images/') !== -1 || userAvatar.indexOf('images/hospital_admins/') !== -1 || userAvatar.indexOf('images/doctors/') !== -1 || userAvatar.indexOf('images/patients/') !== -1)) {
                return false;
            }
            // If avatar is custom uploaded, show remove button
            if (userAvatar && userAvatar.indexOf('uploads/') !== -1) {
                return true;
            }
            // If there's an avatar URL but not from seeded folder
            if (userAvatar) {
                return true;
            }
            return false;
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

        // ============================================
        // FORM SUBMIT
        // ============================================

        document.getElementById('editUserForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            var submitBtn = this.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            var formData = new FormData(this);
            formData.set('_method', 'PUT');

            try {
                var response = await fetch('{{ route("admin.users.update", $user->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    // Upload avatar if selected
                    var avatarFile = document.getElementById('avatarInput').files[0];
                    if (avatarFile) {
                        await uploadAvatar(userId, avatarFile);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'User Updated!',
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
                    Swal.fire('Error', errorMsg, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Network error. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // ============================================
        // INITIALIZE ON PAGE LOAD
        // ============================================

        document.addEventListener('DOMContentLoaded', function () {
            var removeBtn = document.getElementById('removeAvatarBtn');
            if (removeBtn && shouldShowRemoveButton()) {
                removeBtn.style.display = 'inline-block';
            }

            // Clean up flags
            var removeFlag = document.getElementById('remove_avatar_flag');
            if (removeFlag) removeFlag.remove();
        });
    </script>
@endpush