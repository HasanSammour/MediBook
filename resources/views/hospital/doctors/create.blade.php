{{-- resources/views/hospital/doctors/create.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Add New Doctor')
@section('page-title', 'Add New Doctor')
@section('page-subtitle', 'Register a new doctor to your hospital')

@push('styles')
    <style>
        .form-container {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-title {
            font-size: 1.1rem;
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }

        .form-title:first-of-type {
            margin-top: 0;
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

        /* Profile Image Upload */
        .profile-image-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-avatar-preview {
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

        .profile-avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar-preview .default-avatar {
            color: white;
            font-size: 2rem;
            font-weight: 600;
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

        .remove-image-btn {
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

        .remove-image-btn:hover {
            background: #fecaca;
        }

        .upload-note {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-top: 0.5rem;
        }

        /* Availability Grid */
        .availability-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .availability-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .availability-card:hover {
            border-color: var(--primary-light);
        }

        .day-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .day-name {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 22px;
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
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #10b981;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(22px);
        }

        .time-selects {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .time-selects select {
            flex: 1;
            padding: 8px 6px;
            font-size: 0.7rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            cursor: pointer;
        }

        .time-selects span {
            font-size: 0.7rem;
            color: var(--gray-color);
        }

        .disabled-time {
            opacity: 0.5;
            pointer-events: none;
        }

        .info-note {
            background: #f0f9ff;
            padding: 1rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            font-size: 0.8rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .btn-save {
            padding: 12px 28px;
        }

        .btn-cancel {
            padding: 12px 28px;
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
            .form-container {
                padding: 1rem;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .availability-grid {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
            }
            .profile-image-section {
                flex-direction: column;
                text-align: center;
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .time-selects {
                flex-direction: column;
            }
            .time-selects span {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-container">
        <div id="errorMessage" class="alert alert-error" style="display: none;"></div>

        <form id="addDoctorForm" enctype="multipart/form-data">
            @csrf

            <!-- Profile Image Upload Section -->
            <div class="profile-image-section">
                <div class="profile-avatar-preview" id="profile_image_preview">
                    <div class="default-avatar">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <img id="profile_image_img" src="#" alt="Preview" style="display: none;">
                </div>
                <div>
                    <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/jpg" style="display: none;" onchange="previewImage(this)">
                    <button type="button" class="upload-btn" onclick="document.getElementById('profile_image').click()">
                        <i class="fas fa-upload"></i> Upload Photo
                    </button>
                    <button type="button" class="remove-image-btn" onclick="removeImage()" style="display: none;" id="removeImageBtn">
                        <i class="fas fa-times"></i> Remove
                    </button>
                    <div class="upload-note">Recommended: Square image, max 2MB (JPG, PNG)</div>
                </div>
            </div>

            <h3 class="form-title"><i class="fas fa-user-md"></i> Personal Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter doctor's full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter email address" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select id="gender" name="gender">
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
                    <input type="date" id="date_of_birth" name="date_of_birth">
                </div>
            </div>

            <h3 class="form-title"><i class="fas fa-stethoscope"></i> Professional Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Specialty <span class="required">*</span></label>
                    <select id="specialization" name="specialization" required>
                        <option value="">Select Specialty</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}">{{ $specialty }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Consultation Fee ($) <span class="required">*</span></label>
                    <input type="number" id="consultation_fee" name="consultation_fee" placeholder="Enter consultation fee" required>
                </div>
            </div>

            <h3 class="form-title"><i class="fas fa-clock"></i> Working Hours Schedule</h3>
            <p style="font-size: 0.75rem; color: var(--gray-color); margin-bottom: 1rem;">
                <i class="fas fa-info-circle"></i> Break time is automatically set to 12:00 PM - 1:00 PM
            </p>

            <div class="availability-grid" id="availabilityGrid">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $dayKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                @endphp
                @foreach ($days as $index => $day)
                    @php $dayKey = $dayKeys[$index]; @endphp
                    <div class="availability-card" data-day="{{ $dayKey }}">
                        <div class="day-header">
                            <span class="day-name">{{ $day }}</span>
                            <label class="toggle-switch">
                                <input type="checkbox" class="day-toggle" data-day="{{ $dayKey }}" 
                                    {{ in_array($dayKey, ['saturday', 'sunday']) ? '' : 'checked' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="time-selects {{ in_array($dayKey, ['saturday', 'sunday']) ? 'disabled-time' : '' }}" id="time-selects-{{ $dayKey }}">
                            <select class="start-time" data-day="{{ $dayKey }}" {{ in_array($dayKey, ['saturday', 'sunday']) ? 'disabled' : '' }}>
                                <option value="08:00">8:00 AM</option>
                                <option value="08:30">8:30 AM</option>
                                <option value="09:00" selected>9:00 AM</option>
                                <option value="09:30">9:30 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="10:30">10:30 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="11:30">11:30 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="12:30">12:30 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="13:30">1:30 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="14:30">2:30 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="15:30">3:30 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="16:30">4:30 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="17:30">5:30 PM</option>
                                <option value="18:00">6:00 PM</option>
                            </select>
                            <span>to</span>
                            <select class="end-time" data-day="{{ $dayKey }}" {{ in_array($dayKey, ['saturday', 'sunday']) ? 'disabled' : '' }}>
                                <option value="08:30">8:30 AM</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="09:30">9:30 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="10:30">10:30 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="11:30">11:30 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="12:30">12:30 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="13:30">1:30 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="14:30">2:30 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="15:30">3:30 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="16:30">4:30 PM</option>
                                <option value="17:00" selected>5:00 PM</option>
                                <option value="17:30">5:30 PM</option>
                                <option value="18:00">6:00 PM</option>
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Login credentials will be sent automatically to the doctor's email address after registration.</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="fas fa-save"></i> Register Doctor
                </button>
                <a href="{{ route('hospital.doctors.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Dynamic time dropdown functions
    function updateEndTimeDropdown(dayKey, startTime) {
        const endSelect = document.querySelector(`#time-selects-${dayKey} .end-time`);
        if (!endSelect) return;
        
        const currentValue = endSelect.value;
        let options = '';
        
        for (let h = 0; h < 24; h++) {
            for (let m = 0; m < 60; m += 30) {
                const hour24 = h;
                const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                const ampm = hour24 < 12 ? 'AM' : 'PM';
                const timeValue = `${hour24.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
                const displayTime = `${hour12}:${m.toString().padStart(2, '0')} ${ampm}`;
                
                if (timeValue > startTime) {
                    options += `<option value="${timeValue}" ${timeValue === currentValue ? 'selected' : ''}>${displayTime}</option>`;
                }
            }
        }
        
        if (options === '') {
            endSelect.innerHTML = '<option value="">No available times</option>';
        } else {
            endSelect.innerHTML = options;
        }
    }
    
    function updateStartTimeDropdown(dayKey, endTime) {
        const startSelect = document.querySelector(`#time-selects-${dayKey} .start-time`);
        if (!startSelect) return;
        
        const currentValue = startSelect.value;
        let options = '';
        
        for (let h = 0; h < 24; h++) {
            for (let m = 0; m < 60; m += 30) {
                const hour24 = h;
                const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                const ampm = hour24 < 12 ? 'AM' : 'PM';
                const timeValue = `${hour24.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
                const displayTime = `${hour12}:${m.toString().padStart(2, '0')} ${ampm}`;
                
                if (timeValue < endTime) {
                    options += `<option value="${timeValue}" ${timeValue === currentValue ? 'selected' : ''}>${displayTime}</option>`;
                }
            }
        }
        
        if (options === '') {
            startSelect.innerHTML = '<option value="">No available times</option>';
        } else {
            startSelect.innerHTML = options;
        }
    }

    // Initialize availability toggles and time select listeners
    function initAvailabilityGrid() {
        document.querySelectorAll('.day-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const card = this.closest('.availability-card');
                const timeSelects = card.querySelector('.time-selects');
                const startSelect = card.querySelector('.start-time');
                const endSelect = card.querySelector('.end-time');
                
                if (this.checked) {
                    timeSelects.classList.remove('disabled-time');
                    startSelect.disabled = false;
                    endSelect.disabled = false;
                } else {
                    timeSelects.classList.add('disabled-time');
                    startSelect.disabled = true;
                    endSelect.disabled = true;
                }
            });
        });

        document.querySelectorAll('.start-time').forEach(select => {
            select.addEventListener('change', function() {
                const dayKey = this.getAttribute('data-day');
                updateEndTimeDropdown(dayKey, this.value);
            });
        });

        document.querySelectorAll('.end-time').forEach(select => {
            select.addEventListener('change', function() {
                const dayKey = this.getAttribute('data-day');
                updateStartTimeDropdown(dayKey, this.value);
            });
        });
    }

    // Image preview
    function previewImage(input) {
        const previewContainer = document.getElementById('profile_image_preview');
        const previewImg = document.getElementById('profile_image_img');
        const defaultAvatar = previewContainer.querySelector('.default-avatar');
        const removeBtn = document.getElementById('removeImageBtn');
        const errorDiv = document.getElementById('errorMessage');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            // Validate file type
            if (!validTypes.includes(file.type)) {
                if (errorDiv) {
                    errorDiv.innerHTML = 'Invalid file type. Please upload JPG, PNG, or GIF images only.';
                    errorDiv.style.display = 'block';
                } else {
                    Swal.fire('Error', 'Invalid file type. Please upload JPG, PNG, or GIF images only.', 'error');
                }
                input.value = '';
                return;
            }
            
            // Validate file size
            if (file.size > maxSize) {
                if (errorDiv) {
                    errorDiv.innerHTML = 'File is too large. Maximum size is 2MB.';
                    errorDiv.style.display = 'block';
                } else {
                    Swal.fire('Error', 'File is too large. Maximum size is 2MB.', 'error');
                }
                input.value = '';
                return;
            }
            
            // Clear any previous error
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                if (defaultAvatar) defaultAvatar.style.display = 'none';
                removeBtn.style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
        }
    }
    
    function removeImage() {
        const fileInput = document.getElementById('profile_image');
        const previewImg = document.getElementById('profile_image_img');
        const defaultAvatar = document.querySelector('#profile_image_preview .default-avatar');
        const removeBtn = document.getElementById('removeImageBtn');
        
        fileInput.value = '';
        previewImg.src = '#';
        previewImg.style.display = 'none';
        if (defaultAvatar) defaultAvatar.style.display = 'flex';
        removeBtn.style.display = 'none';
    }

    function getWorkingHoursData() {
        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        const workingHours = {};
        
        days.forEach((day) => {
            const card = document.querySelector(`.availability-card[data-day="${day}"]`);
            if (card) {
                const toggle = card.querySelector('.day-toggle');
                const isEnabled = toggle ? toggle.checked : false;
                
                if (isEnabled) {
                    const startSelect = card.querySelector('.start-time');
                    const endSelect = card.querySelector('.end-time');
                    workingHours[`${day}_enabled`] = 'true';
                    workingHours[`${day}_start`] = startSelect ? startSelect.value : '09:00';
                    workingHours[`${day}_end`] = endSelect ? endSelect.value : '17:00';
                } else {
                    workingHours[`${day}_enabled`] = 'false';
                    workingHours[`${day}_start`] = null;
                    workingHours[`${day}_end`] = null;
                }
            }
        });
        
        return workingHours;
    }

    document.getElementById('addDoctorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        const errorDiv = document.getElementById('errorMessage');
        errorDiv.style.display = 'none';
        
        const formData = new FormData(this);
        const workingHoursData = getWorkingHoursData();
        
        for (const [key, value] of Object.entries(workingHoursData)) {
            formData.append(key, value);
        }
        
        try {
            const response = await fetch('{{ route("hospital.doctors.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Doctor Added!',
                    text: data.message,
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                let errorMsg = '';
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

    document.addEventListener('DOMContentLoaded', function() {
        initAvailabilityGrid();
    });
</script>
@endpush