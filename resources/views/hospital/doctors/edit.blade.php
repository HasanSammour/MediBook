{{-- resources/views/hospital/doctors/edit.blade.php --}}
@extends('layouts.hospital')

@section('title', 'Edit Doctor')
@section('page-title', 'Edit Doctor')
@section('page-subtitle', 'Update doctor information and availability')

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

        input:checked+.toggle-slider {
            background-color: #10b981;
        }

        input:checked+.toggle-slider:before {
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

        /* Status Section */
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

        .status-section label {
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 0;
            color: var(--dark-color);
        }

        .status-select {
            width: auto;
            min-width: 150px;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.8rem;
            background: white;
            cursor: pointer;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
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

            .status-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .status-select {
                width: 100%;
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

        <form id="editDoctorForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Image Upload Section -->
            <div class="profile-image-section">
                <div class="profile-avatar-preview" id="profile_image_preview">
                    @if($doctor->profile_image && file_exists(public_path($doctor->profile_image)) && !str_contains($doctor->profile_image, 'images/doctors/'))
                        <img src="{{ asset($doctor->profile_image) }}" alt="{{ $doctor->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @elseif($doctor->profile_image && str_contains($doctor->profile_image, 'images/doctors/'))
                        {{-- Seeded default image - don't show remove button --}}
                        <img src="{{ asset($doctor->profile_image) }}" alt="{{ $doctor->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        <div class="default-avatar" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2563eb, #1d4ed8); border-radius: 50%;">
                            <i class="fas fa-user-md" style="font-size: 2rem; color: white;"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/jpg" style="display: none;" onchange="previewImage(this)">
                    <button type="button" class="upload-btn" onclick="document.getElementById('profile_image').click()">
                        <i class="fas fa-upload"></i> Change Photo
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
                    <input type="text" id="name" name="name" value="{{ old('name', $doctor->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $doctor->email) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $doctor->phone) }}">
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
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth', optional($doctor->date_of_birth)->format('Y-m-d')) }}">
                </div>
            </div>

            <h3 class="form-title"><i class="fas fa-stethoscope"></i> Professional Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Specialty <span class="required">*</span></label>
                    <select id="specialization" name="specialization" required>
                        <option value="">Select Specialty</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}" {{ $doctor->specialization == $specialty ? 'selected' : '' }}>
                                {{ $specialty }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Consultation Fee ($) <span class="required">*</span></label>
                    <input type="number" id="consultation_fee" name="consultation_fee"
                        value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required>
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
                    @php
                        $dayKey = $dayKeys[$index];
                        $dayData = $workingHours[$dayKey] ?? ['enabled' => false, 'start' => '09:00', 'end' => '17:00'];
                        $isEnabled = $dayData['enabled'] ?? false;
                        $startTime = $dayData['start'] ?? '09:00';
                        $endTime = $dayData['end'] ?? '17:00';
                    @endphp
                    <div class="availability-card" data-day="{{ $dayKey }}">
                        <div class="day-header">
                            <span class="day-name">{{ $day }}</span>
                            <label class="toggle-switch">
                                <input type="checkbox" class="day-toggle" data-day="{{ $dayKey }}" {{ $isEnabled ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="time-selects {{ !$isEnabled ? 'disabled-time' : '' }}" id="time-selects-{{ $dayKey }}">
                            <select class="start-time" data-day="{{ $dayKey }}" {{ !$isEnabled ? 'disabled' : '' }}>
                                <option value="08:00" {{ $startTime == '08:00' ? 'selected' : '' }}>8:00 AM</option>
                                <option value="08:30" {{ $startTime == '08:30' ? 'selected' : '' }}>8:30 AM</option>
                                <option value="09:00" {{ $startTime == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="09:30" {{ $startTime == '09:30' ? 'selected' : '' }}>9:30 AM</option>
                                <option value="10:00" {{ $startTime == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="10:30" {{ $startTime == '10:30' ? 'selected' : '' }}>10:30 AM</option>
                                <option value="11:00" {{ $startTime == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="11:30" {{ $startTime == '11:30' ? 'selected' : '' }}>11:30 AM</option>
                                <option value="12:00" {{ $startTime == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                <option value="12:30" {{ $startTime == '12:30' ? 'selected' : '' }}>12:30 PM</option>
                                <option value="13:00" {{ $startTime == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                <option value="13:30" {{ $startTime == '13:30' ? 'selected' : '' }}>1:30 PM</option>
                                <option value="14:00" {{ $startTime == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                <option value="14:30" {{ $startTime == '14:30' ? 'selected' : '' }}>2:30 PM</option>
                                <option value="15:00" {{ $startTime == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                <option value="15:30" {{ $startTime == '15:30' ? 'selected' : '' }}>3:30 PM</option>
                                <option value="16:00" {{ $startTime == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                <option value="16:30" {{ $startTime == '16:30' ? 'selected' : '' }}>4:30 PM</option>
                                <option value="17:00" {{ $startTime == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                <option value="17:30" {{ $startTime == '17:30' ? 'selected' : '' }}>5:30 PM</option>
                                <option value="18:00" {{ $startTime == '18:00' ? 'selected' : '' }}>6:00 PM</option>
                            </select>
                            <span>to</span>
                            <select class="end-time" data-day="{{ $dayKey }}" {{ !$isEnabled ? 'disabled' : '' }}>
                                <option value="08:30" {{ $endTime == '08:30' ? 'selected' : '' }}>8:30 AM</option>
                                <option value="09:00" {{ $endTime == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="09:30" {{ $endTime == '09:30' ? 'selected' : '' }}>9:30 AM</option>
                                <option value="10:00" {{ $endTime == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="10:30" {{ $endTime == '10:30' ? 'selected' : '' }}>10:30 AM</option>
                                <option value="11:00" {{ $endTime == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="11:30" {{ $endTime == '11:30' ? 'selected' : '' }}>11:30 AM</option>
                                <option value="12:00" {{ $endTime == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                <option value="12:30" {{ $endTime == '12:30' ? 'selected' : '' }}>12:30 PM</option>
                                <option value="13:00" {{ $endTime == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                <option value="13:30" {{ $endTime == '13:30' ? 'selected' : '' }}>1:30 PM</option>
                                <option value="14:00" {{ $endTime == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                <option value="14:30" {{ $endTime == '14:30' ? 'selected' : '' }}>2:30 PM</option>
                                <option value="15:00" {{ $endTime == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                <option value="15:30" {{ $endTime == '15:30' ? 'selected' : '' }}>3:30 PM</option>
                                <option value="16:00" {{ $endTime == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                <option value="16:30" {{ $endTime == '16:30' ? 'selected' : '' }}>4:30 PM</option>
                                <option value="17:00" {{ $endTime == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                <option value="17:30" {{ $endTime == '17:30' ? 'selected' : '' }}>5:30 PM</option>
                                <option value="18:00" {{ $endTime == '18:00' ? 'selected' : '' }}>6:00 PM</option>
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Status Section -->
            <div class="status-section">
                <label>Account Status:</label>
                <select id="doctor_status" name="is_active" class="status-select">
                    <option value="1" {{ $doctor->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$doctor->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
                <span id="statusBadge" class="status-badge {{ $doctor->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="fas fa-save"></i> Save Changes
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
        // ============================================
        // TIME SLOT FUNCTIONS
        // ============================================

        function updateEndTimeDropdown(dayKey, startTime) {
            const endSelect = document.querySelector('#time-selects-' + dayKey + ' .end-time');
            if (!endSelect) return;

            const currentValue = endSelect.value;
            let options = '';

            for (let h = 0; h < 24; h++) {
                for (let m = 0; m < 60; m += 30) {
                    const hour24 = h;
                    const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                    const ampm = hour24 < 12 ? 'AM' : 'PM';
                    const timeValue = hour24.toString().padStart(2, '0') + ':' + m.toString().padStart(2, '0');
                    const displayTime = hour12 + ':' + m.toString().padStart(2, '0') + ' ' + ampm;

                    if (timeValue > startTime) {
                        options += '<option value="' + timeValue + '" ' + (timeValue === currentValue ? 'selected' : '') + '>' + displayTime + '</option>';
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
            const startSelect = document.querySelector('#time-selects-' + dayKey + ' .start-time');
            if (!startSelect) return;

            const currentValue = startSelect.value;
            let options = '';

            for (let h = 0; h < 24; h++) {
                for (let m = 0; m < 60; m += 30) {
                    const hour24 = h;
                    const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                    const ampm = hour24 < 12 ? 'AM' : 'PM';
                    const timeValue = hour24.toString().padStart(2, '0') + ':' + m.toString().padStart(2, '0');
                    const displayTime = hour12 + ':' + m.toString().padStart(2, '0') + ' ' + ampm;

                    if (timeValue < endTime) {
                        options += '<option value="' + timeValue + '" ' + (timeValue === currentValue ? 'selected' : '') + '>' + displayTime + '</option>';
                    }
                }
            }

            if (options === '') {
                startSelect.innerHTML = '<option value="">No available times</option>';
            } else {
                startSelect.innerHTML = options;
            }
        }

        function initAvailabilityGrid() {
            document.querySelectorAll('.day-toggle').forEach(function (toggle) {
                toggle.addEventListener('change', function () {
                    var card = this.closest('.availability-card');
                    var timeSelects = card.querySelector('.time-selects');
                    var startSelect = card.querySelector('.start-time');
                    var endSelect = card.querySelector('.end-time');

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

            document.querySelectorAll('.start-time').forEach(function (select) {
                select.addEventListener('change', function () {
                    var dayKey = this.getAttribute('data-day');
                    updateEndTimeDropdown(dayKey, this.value);
                });
            });

            document.querySelectorAll('.end-time').forEach(function (select) {
                select.addEventListener('change', function () {
                    var dayKey = this.getAttribute('data-day');
                    updateStartTimeDropdown(dayKey, this.value);
                });
            });
        }

        // ============================================
        // IMAGE PREVIEW FUNCTION
        // ============================================

        function previewImage(input) {
        var previewContainer = document.getElementById('profile_image_preview');
        var removeBtn = document.getElementById('removeImageBtn');
        var errorDiv = document.getElementById('errorMessage');
        
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            var maxSize = 2 * 1024 * 1024;
            
            // Validate file type
            if (validTypes.indexOf(file.type) === -1) {
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
            
            // Clear error
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
            // Preview the image
            var reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
                // SHOW the remove button when a new image is selected
                if (removeBtn) {
                    removeBtn.style.display = 'inline-block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

        // ============================================
        // REMOVE IMAGE FUNCTION
        // ============================================

        function removeImage() {
            var previewContainer = document.getElementById('profile_image_preview');
            var removeBtn = document.getElementById('removeImageBtn');
            var fileInput = document.getElementById('profile_image');
            var form = document.getElementById('editDoctorForm');

            // Clear the file input
            fileInput.value = '';

            // Add hidden input to mark that image should be removed
            var removeFlag = document.getElementById('remove_image_flag');
            if (!removeFlag) {
                removeFlag = document.createElement('input');
                removeFlag.type = 'hidden';
                removeFlag.name = 'remove_image';
                removeFlag.id = 'remove_image_flag';
                removeFlag.value = '1';
                form.appendChild(removeFlag);
            } else {
                removeFlag.value = '1';
            }

            // Reset preview to default avatar
            var doctorNameInput = document.getElementById('name');
            var doctorName = doctorNameInput ? doctorNameInput.value : 'Doctor';
            var initial = doctorName.charAt(0);

            previewContainer.innerHTML = '<div class="default-avatar" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2563eb, #1d4ed8); border-radius: 50%;"><span style="color: white; font-size: 2rem; font-weight: 600;">' + initial + '</span></div>';

            // Hide remove button
            removeBtn.style.display = 'none';
        }

        // ============================================
        // GET WORKING HOURS DATA
        // ============================================

        function getWorkingHoursData() {
            var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            var workingHours = {};

            days.forEach(function (day) {
                var card = document.querySelector('.availability-card[data-day="' + day + '"]');
                if (card) {
                    var toggle = card.querySelector('.day-toggle');
                    var isEnabled = toggle ? toggle.checked : false;

                    if (isEnabled) {
                        var startSelect = card.querySelector('.start-time');
                        var endSelect = card.querySelector('.end-time');
                        workingHours[day + '_enabled'] = 'true';
                        workingHours[day + '_start'] = startSelect ? startSelect.value : '09:00';
                        workingHours[day + '_end'] = endSelect ? endSelect.value : '17:00';
                    } else {
                        workingHours[day + '_enabled'] = 'false';
                        workingHours[day + '_start'] = null;
                        workingHours[day + '_end'] = null;
                    }
                }
            });

            return workingHours;
        }

        // ============================================
        // UPDATE STATUS BADGE
        // ============================================

        document.getElementById('doctor_status')?.addEventListener('change', function () {
            var badge = document.getElementById('statusBadge');
            if (this.value == '1') {
                badge.className = 'status-badge status-active';
                badge.textContent = 'Active';
            } else {
                badge.className = 'status-badge status-inactive';
                badge.textContent = 'Inactive';
            }
        });

        // ============================================
        // FORM SUBMIT
        // ============================================

        document.getElementById('editDoctorForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            var submitBtn = this.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            var errorDiv = document.getElementById('errorMessage');
            errorDiv.style.display = 'none';

            var formData = new FormData(this);
            formData.set('_method', 'PUT');

            var workingHoursData = getWorkingHoursData();
            for (var key in workingHoursData) {
                if (workingHoursData.hasOwnProperty(key)) {
                    formData.append(key, workingHoursData[key]);
                }
            }

            var status = document.getElementById('doctor_status').value;
            formData.append('is_active', status === '1' ? true : false);

            try {
                var response = await fetch('{{ route("hospital.doctors.update", $doctor->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Doctor Updated!',
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
        // DOM CONTENT LOADED
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            initAvailabilityGrid();

            // ============================================
            // SHOW REMOVE BUTTON FOR EXISTING CUSTOM IMAGES
            // ============================================

            var previewContainer = document.getElementById('profile_image_preview');
            var removeBtn = document.getElementById('removeImageBtn');
            var doctorId = {{ $doctor->id }};

            // Function to check if image exists and is not a seeded default
            function shouldShowRemoveButton() {
                // Check if there's an img element in the preview
                var imgElement = previewContainer.querySelector('img');

                if (!imgElement) {
                    return false;
                }

                var src = imgElement.src;

                // If it's a seeded default image (from images/doctors/ folder), show remove button
                if (src && src.indexOf('/images/doctors/') !== -1) {
                    return true;
                }

                // If it's a data URL (newly selected image), show remove button
                if (src && src.indexOf('data:image') !== -1) {
                    return true;
                }

                // If it's a custom uploaded image (from uploads/doctors/), show remove button
                if (src && src.indexOf('/uploads/doctors/') !== -1) {
                    return true;
                }

                // Also check via AJAX if the doctor has a custom profile_image in database
                // This handles cases where the image URL might be relative
                var hasCustomImage = {{ $doctor->profile_image && !str_contains($doctor->profile_image, 'images/doctors/') ? 'true' : 'false' }};

                return hasCustomImage;
            }

            // Show or hide remove button
            if (removeBtn) {
                if (shouldShowRemoveButton()) {
                    removeBtn.style.display = 'inline-block';
                    console.log('Remove button shown - custom image detected');
                } else {
                    removeBtn.style.display = 'none';
                    console.log('Remove button hidden - seeded default image or no image');
                }
            }

            // Clean up any leftover flags from previous submits
            var removeFlag = document.getElementById('remove_image_flag');
            if (removeFlag) removeFlag.remove();

            var newImageFlag = document.getElementById('new_image_selected');
            if (newImageFlag) newImageFlag.remove();
        });
    </script>
@endpush