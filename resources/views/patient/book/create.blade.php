@extends('layouts.patient')

@section('title', 'Book Appointment')

@section('page-title', 'Book Appointment')
@section('page-subtitle', 'Schedule your appointment with ' . $doctor->name)

@push('styles')
    <style>
        .booking-container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 1;
        }

        .step {
            text-align: center;
            position: relative;
            z-index: 2;
            background: #f9fafb;
            flex: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 600;
            color: var(--gray-color);
        }

        .step.active .step-number {
            background: var(--primary-color);
            color: white;
        }

        .step.completed .step-number {
            background: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: var(--gray-color);
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Booking Card */
        .booking-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        /* Doctor Info Card */
        .doctor-info-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 1.5rem;
        }

        .doctor-avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-avatar-lg svg {
            width: 80px;
            height: 80px;
        }

        .doctor-details-lg h3 {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .doctor-details-lg p {
            color: var(--gray-color);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .doctor-fee-lg {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark-color);
        }

        .date-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .time-slot-warning {
            background: #fef3c7;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.3s ease;
        }

        .time-slot-warning i {
            font-size: 1rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Time Slots */
        .slots-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        .time-slot {
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            border-color: var(--primary-color);
            background: rgba(37, 99, 235, 0.05);
        }

        .time-slot.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .time-slot.disabled {
            background: #f9fafb;
            color: #cbd5e1;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* Notes */
        .notes-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            resize: vertical;
        }

        /* Summary Card */
        .summary-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .total-row {
            font-weight: 700;
            color: var(--dark-color);
            font-size: 1rem;
        }

        /* Loading */
        .loading-slots {
            text-align: center;
            padding: 40px;
        }

        .loading-spinner-sm {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-card {
                padding: 1.5rem;
            }

            .doctor-info-card {
                flex-direction: column;
                text-align: center;
            }

            .slots-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .progress-steps {
                flex-direction: column;
                gap: 1rem;
            }

            .progress-steps::before {
                display: none;
            }

            .step {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .step-number {
                margin: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="booking-container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <div class="step-label">Select Date</div>
            </div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <div class="step-label">Select Time</div>
            </div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <div class="step-label">Confirm Booking</div>
            </div>
        </div>

        <!-- Step 1: Select Date -->
        <div id="step1Content" class="booking-card">
            <div class="doctor-info-card">
                <div class="doctor-avatar-lg">
                    {!! $doctor->avatar_html !!}
                </div>
                <div class="doctor-details-lg">
                    <h3>{{ $doctor->name }}</h3>
                    <p>{{ $doctor->specialization ?? 'General Physician' }}</p>
                    <p>{{ $doctor->hospital->name ?? 'Independent Practice' }}</p>
                    <div class="doctor-fee-lg">Consultation Fee: ${{ number_format($doctor->consultation_fee ?? 100, 2) }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Select Date <span class="required">*</span></label>
                <input type="date" id="appointmentDate" class="date-input" min="{{ date('Y-m-d') }}">
            </div>

            <button class="btn btn-primary" id="nextToTime" style="width: 100%;" disabled>Continue to Time Slot →</button>
        </div>

        <!-- Step 2: Select Time -->
        <div id="step2Content" class="booking-card" style="display: none;">
            <div class="form-group">
                <label>Available Time Slots <span class="required">*</span></label>
                <div id="timeSlotsGrid" class="slots-grid">
                    <div class="loading-slots">
                        <div class="loading-spinner-sm"></div> Loading time slots...
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button class="btn btn-outline" id="backToDate" style="flex: 1;">← Back</button>
                <button class="btn btn-primary" id="nextToConfirm" style="flex: 1;" disabled>Continue to Confirm →</button>
            </div>
        </div>

        <!-- Step 3: Confirm Booking -->
        <div id="step3Content" class="booking-card" style="display: none;">
            <h3 style="margin-bottom: 1rem;">Appointment Summary</h3>
            <div class="summary-card">
                <div class="summary-row">
                    <span>Doctor</span>
                    <span id="summaryDoctor">{{ $doctor->name }}</span>
                </div>
                <div class="summary-row">
                    <span>Specialty</span>
                    <span id="summarySpecialty">{{ $doctor->specialization ?? 'General Physician' }}</span>
                </div>
                <div class="summary-row">
                    <span>Hospital</span>
                    <span id="summaryHospital">{{ $doctor->hospital->name ?? 'Independent Practice' }}</span>
                </div>
                <div class="summary-row">
                    <span>Date</span>
                    <span id="summaryDate">-</span>
                </div>
                <div class="summary-row">
                    <span>Time</span>
                    <span id="summaryTime">-</span>
                </div>
                <div class="summary-row">
                    <span>Consultation Fee</span>
                    <span id="summaryFee">${{ number_format($doctor->consultation_fee ?? 100, 2) }}</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total Amount</span>
                    <span id="summaryTotal">${{ number_format($doctor->consultation_fee ?? 100, 2) }}</span>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label>Additional Notes (Optional)</label>
                <textarea id="bookingNotes" class="notes-input" rows="3"
                    placeholder="Any symptoms or special requests?"></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button class="btn btn-outline" id="backToTime" style="flex: 1;">← Back</button>
                <button class="btn btn-success" id="confirmBooking" style="flex: 1;">Confirm Booking ✓</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedDate = '';
        let selectedTime = '';
        let availableTimeSlots = [];

        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('appointmentDate');
            const nextToTimeBtn = document.getElementById('nextToTime');

            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;

            dateInput.addEventListener('change', function () {
                selectedDate = this.value;
                nextToTimeBtn.disabled = false;
            });

            nextToTimeBtn.addEventListener('click', function () {
                selectedDate = document.getElementById('appointmentDate').value;

                if (!selectedDate) {
                    Swal.fire('Error', 'Please select a date.', 'error');
                    return;
                }

                // Check if selected date is in the past
                const selectedDateObj = new Date(selectedDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDateObj < today) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date',
                        text: 'You cannot book an appointment for a past date. Please select today or a future date.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                // Also prevent selecting dates more than 90 days in future (optional)
                const maxDate = new Date();
                maxDate.setDate(maxDate.getDate() + 90);
                if (selectedDateObj > maxDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Date Too Far',
                        text: 'You can only book appointments up to 90 days in advance.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                // Show step 2 and load time slots
                document.getElementById('step1').classList.remove('active');
                document.getElementById('step1').classList.add('completed');
                document.getElementById('step2').classList.add('active');
                document.getElementById('step1Content').style.display = 'none';
                document.getElementById('step2Content').style.display = 'block';

                loadTimeSlots();
            });

            document.getElementById('backToDate').addEventListener('click', function () {
                document.getElementById('step2').classList.remove('active');
                document.getElementById('step1').classList.add('active');
                document.getElementById('step1').classList.remove('completed');
                document.getElementById('step2Content').style.display = 'none';
                document.getElementById('step1Content').style.display = 'block';
                selectedTime = '';
            });

            document.getElementById('nextToConfirm').addEventListener('click', function () {
                if (!selectedTime) return;

                document.getElementById('step2').classList.remove('active');
                document.getElementById('step2').classList.add('completed');
                document.getElementById('step3').classList.add('active');
                document.getElementById('step2Content').style.display = 'none';
                document.getElementById('step3Content').style.display = 'block';

                // Update summary
                const date = new Date(selectedDate);
                document.getElementById('summaryDate').innerText = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                document.getElementById('summaryTime').innerText = selectedTime;
            });

            document.getElementById('backToTime').addEventListener('click', function () {
                document.getElementById('step3').classList.remove('active');
                document.getElementById('step2').classList.add('active');
                document.getElementById('step2').classList.remove('completed');
                document.getElementById('step3Content').style.display = 'none';
                document.getElementById('step2Content').style.display = 'block';
            });

            document.getElementById('confirmBooking').addEventListener('click', confirmBooking);
        });

        async function loadTimeSlots() {
            const container = document.getElementById('timeSlotsGrid');
            container.innerHTML = '<div class="loading-slots"><div class="loading-spinner-sm"></div> Loading time slots...</div>';

            // Remove any existing warning before loading new slots
            const existingWarning = document.getElementById('timeSlotWarning');
            if (existingWarning) {
                existingWarning.remove();
            }

            try {
                const response = await fetch(`/patient/doctors/{{ $doctor->id }}/available-slots?date=${selectedDate}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                console.log('Slots response:', data);

                if (data.success) {
                    // Filter to show only available slots
                    const availableSlots = data.slots.filter(slot => slot.available === true);

                    console.log('Available slots count:', availableSlots.length);

                    if (availableSlots.length === 0) {
                        container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px; background: #fef3c7; border-radius: 12px; color: #92400e;">⚠️ No available slots for this date. Please select another date.</div>';
                        return;
                    }

                    // Render only available slots
                    container.innerHTML = availableSlots.map(slot => `
                    <div class="time-slot" data-time="${slot.value}">
                        ${slot.display}
                    </div>
                `).join('');

                    // Add click event
                    document.querySelectorAll('.time-slot').forEach(el => {
                        el.addEventListener('click', function () {
                            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            selectedTime = this.getAttribute('data-time');
                            document.getElementById('nextToConfirm').disabled = false;
                        });
                    });

                    // ============================================
                    // Show warning ONLY for today and ONLY if there were past slots
                    // ============================================
                    const hiddenSlotsCount = data.slots.filter(slot => slot.is_past === true).length;

                    if (data.is_today === true && hiddenSlotsCount > 0) {
                        // Remove any existing warning first
                        let warningDiv = document.getElementById('timeSlotWarning');
                        if (warningDiv) {
                            warningDiv.remove();
                        }

                        // Create new warning
                        warningDiv = document.createElement('div');
                        warningDiv.id = 'timeSlotWarning';
                        warningDiv.className = 'time-slot-warning';
                        warningDiv.innerHTML = `<i class="fas fa-info-circle"></i> Past time slots for today have been automatically hidden.`;

                        // Insert warning above the time slots grid
                        container.parentNode.insertBefore(warningDiv, container);

                        // Auto-hide after 5 seconds
                        setTimeout(function () {
                            const warning = document.getElementById('timeSlotWarning');
                            if (warning) {
                                warning.style.opacity = '0';
                                warning.style.transition = 'opacity 0.5s ease';
                                setTimeout(function () {
                                    if (warning && warning.parentNode) {
                                        warning.remove();
                                    }
                                }, 500);
                            }
                        }, 5000);
                    }
                }
            } catch (error) {
                console.error('Error loading slots:', error);
                container.innerHTML = '<div class="loading-slots">Error loading time slots. Please try again.</div>';
            }
        }

        function renderTimeSlots(slots) {
            const container = document.getElementById('timeSlotsGrid');

            // Filter to show only available slots
            const availableSlots = slots.filter(slot => slot.available === true);

            if (availableSlots.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px; background: #fef3c7; border-radius: 12px; color: #92400e;">⚠️ No available slots for this date. Please select another date.</div>';
                return;
            }

            container.innerHTML = availableSlots.map(slot => `
                                        <div class="time-slot" data-time="${slot.value}">
                                            ${slot.display}
                                        </div>
                                    `).join('');

            // Add click event to available slots
            document.querySelectorAll('.time-slot').forEach(el => {
                el.addEventListener('click', function () {
                    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedTime = this.getAttribute('data-time');
                    document.getElementById('nextToConfirm').disabled = false;
                });
            });
        }

        async function confirmBooking() {
            const notes = document.getElementById('bookingNotes').value;

            // Validate before sending
            if (!selectedDate || !selectedTime) {
                Swal.fire('Error', 'Please select date and time.', 'error');
                return;
            }

            Swal.fire({
                title: 'Processing Booking...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const requestData = {
                    doctor_id: {{ $doctor->id }},
                    appointment_date: selectedDate,
                    time: selectedTime,
                    notes: notes
                };

                console.log('Sending booking request:', requestData);

                const response = await fetch('{{ route("patient.book.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();
                console.log('Response data:', data);

                if (response.status === 422) {
                    // Validation error - show the actual message
                    let errorMessage = 'Validation failed: ';
                    if (data.message) {
                        if (typeof data.message === 'object') {
                            errorMessage = Object.values(data.message).flat().join(', ');
                        } else {
                            errorMessage = data.message;
                        }
                    }
                    Swal.fire('Error', errorMessage, 'error');
                    return;
                }

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Appointment Booked!',
                        html: `
                            <div style="text-align: left;">
                                <p><strong>Confirmation #:</strong> ${data.appointment_id}</p>
                                <p><strong>Doctor:</strong> {{ $doctor->name }}</p>
                                <p><strong>Date:</strong> ${new Date(selectedDate).toLocaleDateString()}</p>
                                <p><strong>Time:</strong> ${selectedTime}</p>
                                <p><strong>Status:</strong> <span style="color: #f59e0b;">Pending Confirmation</span></p>
                            </div>
                        `,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Go to My Appointments'
                    }).then(() => {
                        window.location.href = '{{ route("patient.appointments.index") }}';
                    });
                } else {
                    Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                }
            } catch (error) {
                console.error('Booking error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: error.message || 'Network error. Please try again.',
                    confirmButtonColor: '#2563eb'
                });
            }
        }
    </script>
@endpush