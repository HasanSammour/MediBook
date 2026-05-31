{{-- resources/views/admin/hospitals/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add New Hospital')
@section('page-title', 'Add New Hospital')
@section('page-subtitle', 'Register a new hospital on the platform')

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
            font-size: 1.1rem;
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

        /* Logo Upload Section */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
            flex-wrap: wrap;
        }

        .logo-preview {
            width: 100px;
            height: 100px;
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-preview .default-icon {
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

        .logo-status {
            font-size: 0.7rem;
            margin-top: 0.5rem;
        }

        .logo-status.success {
            color: #10b981;
        }

        .logo-status.error {
            color: #ef4444;
        }

        .remove-logo-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 0.5rem;
        }

        .remove-logo-btn:hover {
            background: #fecaca;
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

            .logo-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title"><i class="fas fa-hospital"></i> Hospital Information</h2>

            <div id="errorMessage" class="alert alert-error" style="display: none;"></div>

            <!-- Logo Upload Section - Separate from main form -->
            <div class="logo-section">
                <div class="logo-preview" id="logoPreview">
                    <div class="default-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <img id="logoImg" src="#" alt="Preview" style="display: none;">
                </div>
                <div>
                    <input type="file" id="logoInput" name="logo" accept="image/jpeg,image/png,image/jpg"
                        style="display: none;" onchange="previewLogo(this)">
                    <button type="button" class="upload-btn" onclick="document.getElementById('logoInput').click()">
                        <i class="fas fa-upload"></i> Upload Logo (Optional)
                    </button>
                    <button type="button" class="remove-logo-btn" onclick="removeLogo()" style="display: none;"
                        id="removeLogoBtn">
                        <i class="fas fa-times"></i> Remove Logo
                    </button>
                    <div class="upload-note">Recommended: Square image, max 2MB (JPG, PNG)</div>
                    <div id="logoStatus" class="logo-status"></div>
                </div>
            </div>

            <!-- Main Form - No logo field here -->
            <form id="addHospitalForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label>Hospital Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Enter hospital name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Street Address <span class="required">*</span></label>
                    <input type="text" id="address" name="address" placeholder="Enter full address" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save"></i> Register Hospital
                    </button>
                    <a href="{{ route('admin.hospitals.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentHospitalId = null;

        // ============================================
        // LOGO PREVIEW FUNCTION
        // ============================================

        function previewLogo(input) {
            var previewDiv = document.getElementById('logoPreview');
            var previewImg = document.getElementById('logoImg');
            var defaultIcon = previewDiv.querySelector('.default-icon');
            var removeBtn = document.getElementById('removeLogoBtn');
            var logoStatus = document.getElementById('logoStatus');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                var maxSize = 2 * 1024 * 1024; // 2MB

                // Validate file type
                if (validTypes.indexOf(file.type) === -1) {
                    if (logoStatus) {
                        logoStatus.innerHTML = '<span style="color: #ef4444;">Invalid file type. Please upload JPG, PNG, or GIF images only.</span>';
                        logoStatus.style.display = 'block';
                    } else {
                        Swal.fire('Error', 'Invalid file type. Please upload JPG, PNG, or GIF images only.', 'error');
                    }
                    input.value = '';
                    return;
                }

                // Validate file size
                if (file.size > maxSize) {
                    if (logoStatus) {
                        logoStatus.innerHTML = '<span style="color: #ef4444;">File is too large. Maximum size is 2MB.</span>';
                        logoStatus.style.display = 'block';
                    } else {
                        Swal.fire('Error', 'File is too large. Maximum size is 2MB.', 'error');
                    }
                    input.value = '';
                    return;
                }

                // Clear error
                if (logoStatus) {
                    logoStatus.innerHTML = '';
                    logoStatus.style.display = 'none';
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
        // REMOVE LOGO FUNCTION (for create page)
        // ============================================

        function removeLogo() {
            var fileInput = document.getElementById('logoInput');
            var previewImg = document.getElementById('logoImg');
            var defaultIcon = document.querySelector('#logoPreview .default-icon');
            var removeBtn = document.getElementById('removeLogoBtn');
            var logoStatus = document.getElementById('logoStatus');

            // Clear the file input
            fileInput.value = '';

            // Reset preview
            previewImg.src = '#';
            previewImg.style.display = 'none';
            if (defaultIcon) defaultIcon.style.display = 'flex';

            // Hide remove button
            if (removeBtn) removeBtn.style.display = 'none';

            // Clear status
            if (logoStatus) {
                logoStatus.innerHTML = '';
                logoStatus.style.display = 'none';
            }
        }

        // ============================================
        // FORM SUBMIT
        // ============================================

        document.getElementById('addHospitalForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            var submitBtn = this.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            var errorDiv = document.getElementById('errorMessage');
            errorDiv.style.display = 'none';

            var formData = new FormData(this);

            try {
                var response = await fetch('{{ route("admin.hospitals.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    currentHospitalId = data.hospital_id;

                    // If logo was selected, upload it now
                    var logoFile = document.getElementById('logoInput').files[0];
                    if (logoFile) {
                        await uploadLogo(currentHospitalId, logoFile);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Hospital Added!',
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

        // ============================================
        // UPLOAD LOGO FUNCTION
        // ============================================

        async function uploadLogo(hospitalId, file) {
            var formData = new FormData();
            formData.append('logo', file);
            formData.append('_token', '{{ csrf_token() }}');

            var logoStatus = document.getElementById('logoStatus');
            if (logoStatus) {
                logoStatus.innerHTML = '<span style="color: #f59e0b;">Uploading logo...</span>';
                logoStatus.style.display = 'block';
            }

            try {
                var response = await fetch('/admin/hospitals/' + hospitalId + '/upload-logo', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    if (logoStatus) {
                        logoStatus.innerHTML = '<span style="color: #10b981;">✓ Logo uploaded successfully!</span>';
                        setTimeout(function () {
                            logoStatus.style.display = 'none';
                        }, 3000);
                    }
                } else {
                    if (logoStatus) {
                        logoStatus.innerHTML = '<span style="color: #ef4444;">✗ Failed to upload logo.</span>';
                    }
                }
            } catch (error) {
                console.error('Error uploading logo:', error);
                if (logoStatus) {
                    logoStatus.innerHTML = '<span style="color: #ef4444;">✗ Error uploading logo.</span>';
                }
            }
        }
    </script>
@endpush