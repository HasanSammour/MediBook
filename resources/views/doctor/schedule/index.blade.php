@extends('layouts.doctor')

@section('title', 'My Schedule')

@section('page-title', 'My Schedule')
@section('page-subtitle', 'View and manage your weekly working hours and appointments')

@push('styles')
    <style>
        .calendar-container {
            background: var(--white);
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            min-height: 600px;
        }

        .fc {
            font-family: inherit;
            max-width: 100%;
        }

        .fc-toolbar {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .fc-toolbar-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .fc-button {
            background: var(--primary-color) !important;
            border: none !important;
            padding: 0.4rem 0.8rem !important;
            font-size: 0.8rem !important;
            border-radius: 8px !important;
        }

        .fc-event {
            border-radius: 6px;
            padding: 2px 4px;
            font-size: 0.7rem;
            cursor: pointer;
        }

        .working-hours-panel {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .panel-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }

        .hours-table {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .hours-row {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .day-cell {
            width: 100px;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-indicator.working {
            background: #10b981;
        }

        .status-indicator.off {
            background: #ef4444;
        }

        .time-cell {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .time-select {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.8rem;
            background: white;
            min-width: 100px;
            cursor: pointer;
        }

        .status-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .toggle-btn {
            padding: 5px 14px;
            border: none;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn.working {
            background: #d1fae5;
            color: #065f46;
        }

        .toggle-btn.working:hover {
            background: #10b981;
            color: white;
        }

        .toggle-btn.off {
            background: #fee2e2;
            color: #991b1b;
        }

        .toggle-btn.off:hover {
            background: #ef4444;
            color: white;
        }

        .save-hours-btn {
            padding: 10px 24px;
            margin-top: 0.5rem;
        }

        .break-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .break-section h4 {
            font-size: 0.9rem;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .break-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .break-controls select {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.8rem;
            background: white;
            cursor: pointer;
        }

        .loading-spinner {
            text-align: center;
            padding: 20px;
        }

        .loading-spinner i {
            font-size: 1.5rem;
            color: var(--primary-color);
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

        @media (max-width: 768px) {
            .hours-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .day-cell {
                width: 100%;
            }

            .time-cell {
                width: 100%;
            }

            .status-cell {
                width: 100%;
            }

            .break-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .fc-toolbar {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

    <div class="working-hours-panel">
        <h3 class="panel-title"><i class="fas fa-clock"></i> Working Hours</h3>

        <div id="hoursLoading" class="loading-spinner" style="padding: 20px;">
            <i class="fas fa-spinner fa-spin"></i> Loading working hours...
        </div>

        <div id="hoursTable" class="hours-table" style="display: none;"></div>

        <button class="btn btn-primary save-hours-btn" onclick="saveWorkingHours()" style="display: none;" id="saveBtn">
            <i class="fas fa-save"></i> Save Working Hours
        </button>

        <div class="break-section">
            <h4><i class="fas fa-utensils"></i> Lunch Break</h4>
            <div class="break-controls">
                <select id="breakStart">
                    <option value="12:00">12:00 PM</option>
                    <option value="12:30">12:30 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="13:30">1:30 PM</option>
                    <option value="14:00">2:00 PM</option>
                </select>
                <span>to</span>
                <select id="breakEnd">
                    <option value="13:00">1:00 PM</option>
                    <option value="13:30">1:30 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="14:30">2:30 PM</option>
                    <option value="15:00">3:00 PM</option>
                </select>
                <button class="btn btn-outline btn-sm" onclick="saveBreakTime()">Save Break</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let calendar = null;

        // Generate time options with optional min time
        function generateTimeOptions(selected, minTime = null, maxTime = null) {
            let options = '';
            for (let h = 0; h < 24; h++) {
                for (let m = 0; m < 60; m += 30) {
                    const hour24 = h;
                    const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                    const ampm = hour24 < 12 ? 'AM' : 'PM';
                    const timeValue = `${hour24.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
                    const displayTime = `${hour12}:${m.toString().padStart(2, '0')} ${ampm}`;

                    // Apply min/max constraints
                    if (minTime && timeValue <= minTime) continue;
                    if (maxTime && timeValue >= maxTime) continue;

                    options += `<option value="${timeValue}" ${timeValue === selected ? 'selected' : ''}>${displayTime}</option>`;
                }
            }
            return options;
        }

        // Get all time options (no constraints)
        function getAllTimeOptions(selected) {
            let options = '';
            for (let h = 0; h < 24; h++) {
                for (let m = 0; m < 60; m += 30) {
                    const hour24 = h;
                    const hour12 = hour24 % 12 === 0 ? 12 : hour24 % 12;
                    const ampm = hour24 < 12 ? 'AM' : 'PM';
                    const timeValue = `${hour24.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
                    const displayTime = `${hour12}:${m.toString().padStart(2, '0')} ${ampm}`;
                    options += `<option value="${timeValue}" ${timeValue === selected ? 'selected' : ''}>${displayTime}</option>`;
                }
            }
            return options;
        }

        // Update end time dropdown based on selected start time
        function updateEndTimeDropdown(dayId, startTime) {
            const endSelect = document.getElementById(`${dayId}_end`);
            if (!endSelect) return;

            const currentEndValue = endSelect.value;
            let newOptions = generateTimeOptions(currentEndValue, startTime);

            // If no options available (start time is too late), show message
            if (!newOptions) {
                endSelect.innerHTML = '<option value="">No available times</option>';
            } else {
                endSelect.innerHTML = newOptions;
            }
        }

        // Update start time dropdown based on selected end time
        function updateStartTimeDropdown(dayId, endTime) {
            const startSelect = document.getElementById(`${dayId}_start`);
            if (!startSelect) return;

            const currentStartValue = startSelect.value;
            let newOptions = generateTimeOptions(currentStartValue, null, endTime);

            if (!newOptions) {
                startSelect.innerHTML = '<option value="">No available times</option>';
            } else {
                startSelect.innerHTML = newOptions;
            }
        }

        // Create time selects with dynamic behavior for a specific day
        function createTimeSelects(dayId, startTime, endTime, isWorking) {
            if (!isWorking) {
                return `<span style="color: var(--gray-color);">Not working</span>`;
            }

            // Generate all time options for start
            let startOptions = getAllTimeOptions(startTime);
            let endOptions = getAllTimeOptions(endTime);

            return `
            <select id="${dayId}_start" class="time-select start-time" data-day="${dayId}">
                ${startOptions}
            </select>
            <span>to</span>
            <select id="${dayId}_end" class="time-select end-time" data-day="${dayId}">
                ${endOptions}
            </select>
        `;
        }

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                nowIndicator: true,
                editable: false,
                selectable: false,
                height: 'auto',
                events: '{{ route("doctor.schedule.events") }}',
                eventClick: function (info) {
                    viewAppointmentDetails(info.event);
                }
            });
            calendar.render();
        }

        async function renderWorkingHours() {
            const container = document.getElementById('hoursTable');
            const loadingDiv = document.getElementById('hoursLoading');
            const saveBtn = document.getElementById('saveBtn');

            loadingDiv.style.display = 'block';
            container.style.display = 'none';
            saveBtn.style.display = 'none';

            try {
                const response = await fetch('{{ route("doctor.schedule.working-hours") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.success) {
                    if (data.break_time) {
                        const breakStartSelect = document.getElementById('breakStart');
                        const breakEndSelect = document.getElementById('breakEnd');
                        if (breakStartSelect) breakStartSelect.value = data.break_time.start;
                        if (breakEndSelect) breakEndSelect.value = data.break_time.end;
                    }

                    let html = '';
                    const days = [
                        { key: 'monday', name: 'Monday' },
                        { key: 'tuesday', name: 'Tuesday' },
                        { key: 'wednesday', name: 'Wednesday' },
                        { key: 'thursday', name: 'Thursday' },
                        { key: 'friday', name: 'Friday' },
                        { key: 'saturday', name: 'Saturday' },
                        { key: 'sunday', name: 'Sunday' }
                    ];

                    days.forEach(day => {
                        const scheduleData = data.schedule.find(s => s.day === day.name);
                        if (scheduleData) {
                            let startTime = '09:00';
                            let endTime = '17:00';

                            if (scheduleData.hours !== 'Closed') {
                                const hoursParts = scheduleData.hours.split(' - ');
                                startTime = hoursParts[0];
                                endTime = hoursParts[1] ? hoursParts[1].split(' ')[0] : '17:00';
                            }

                            html += `
                            <div class="hours-row" data-day="${day.key}">
                                <div class="day-cell">
                                    <span class="status-indicator ${scheduleData.available ? 'working' : 'off'}"></span>
                                    ${day.name}
                                </div>
                                <div class="time-cell" id="time-cell-${day.key}">
                                    ${createTimeSelects(day.key, startTime, endTime, scheduleData.available)}
                                </div>
                                <div class="status-cell">
                                    <button class="toggle-btn ${scheduleData.available ? 'working' : 'off'}" onclick="toggleDay('${day.key}', '${day.name}')">
                                        ${scheduleData.available ? '<i class="fas fa-check-circle"></i> Working' : '<i class="fas fa-ban"></i> Day Off'}
                                    </button>
                                </div>
                            </div>
                        `;
                        }
                    });

                    container.innerHTML = html;

                    // Add event listeners for dynamic time selects
                    days.forEach(day => {
                        const startSelect = document.getElementById(`${day.key}_start`);
                        const endSelect = document.getElementById(`${day.key}_end`);

                        if (startSelect) {
                            startSelect.addEventListener('change', function () {
                                updateEndTimeDropdown(day.key, this.value);
                            });
                        }
                        if (endSelect) {
                            endSelect.addEventListener('change', function () {
                                updateStartTimeDropdown(day.key, this.value);
                            });
                        }
                    });

                    loadingDiv.style.display = 'none';
                    container.style.display = 'block';
                    saveBtn.style.display = 'inline-block';
                }
            } catch (error) {
                console.error('Error:', error);
                loadingDiv.innerHTML = '<p style="color: red;">Error loading schedule</p>';
            }
        }

        window.toggleDay = function (dayKey, dayName) {
            const row = document.querySelector(`.hours-row[data-day="${dayKey}"]`);
            const timeCell = document.getElementById(`time-cell-${dayKey}`);
            const toggleBtn = row.querySelector('.toggle-btn');
            const isWorking = toggleBtn.classList.contains('working');

            if (isWorking) {
                // Turn off
                toggleBtn.classList.remove('working');
                toggleBtn.classList.add('off');
                toggleBtn.innerHTML = '<i class="fas fa-ban"></i> Day Off';
                timeCell.innerHTML = '<span style="color: var(--gray-color);">Not working</span>';
            } else {
                // Turn on
                toggleBtn.classList.remove('off');
                toggleBtn.classList.add('working');
                toggleBtn.innerHTML = '<i class="fas fa-check-circle"></i> Working';

                // Reset to default times
                const defaultStart = '09:00';
                const defaultEnd = '17:00';
                timeCell.innerHTML = createTimeSelects(dayKey, defaultStart, defaultEnd, true);

                // Add event listeners to new selects
                const startSelect = document.getElementById(`${dayKey}_start`);
                const endSelect = document.getElementById(`${dayKey}_end`);

                if (startSelect) {
                    startSelect.addEventListener('change', function () {
                        updateEndTimeDropdown(dayKey, this.value);
                    });
                }
                if (endSelect) {
                    endSelect.addEventListener('change', function () {
                        updateStartTimeDropdown(dayKey, this.value);
                    });
                }
            }
        };

        window.saveWorkingHours = async function () {
            const days = [
                { key: 'monday', name: 'Monday' },
                { key: 'tuesday', name: 'Tuesday' },
                { key: 'wednesday', name: 'Wednesday' },
                { key: 'thursday', name: 'Thursday' },
                { key: 'friday', name: 'Friday' },
                { key: 'saturday', name: 'Saturday' },
                { key: 'sunday', name: 'Sunday' }
            ];

            const formData = new FormData();
            const errors = [];

            const breakStart = document.getElementById('breakStart').value;
            const breakEnd = document.getElementById('breakEnd').value;

            formData.append('break_start', breakStart);
            formData.append('break_end', breakEnd);

            // Validate each day
            for (const day of days) {
                const row = document.querySelector(`.hours-row[data-day="${day.key}"]`);
                if (row) {
                    const toggleBtn = row.querySelector('.toggle-btn');
                    const isWorking = toggleBtn.classList.contains('working');

                    formData.append(`${day.key}_enabled`, isWorking ? 'true' : 'false');

                    if (isWorking) {
                        const startSelect = document.getElementById(`${day.key}_start`);
                        const endSelect = document.getElementById(`${day.key}_end`);

                        if (startSelect && endSelect) {
                            const startTime = startSelect.value;
                            const endTime = endSelect.value;

                            formData.append(`${day.key}_start`, startTime);
                            formData.append(`${day.key}_end`, endTime);

                            // Validate start time is before end time
                            if (startTime >= endTime) {
                                errors.push(day.name);
                            }
                        }
                    }
                }
            }

            // Show error if any day has invalid times
            if (errors.length > 0) {
                const errorMessage = errors.length === 1
                    ? `${errors[0]} has invalid working hours. Start time must be before end time.`
                    : `${errors.join(', ')} have invalid working hours. Start time must be before end time for each day.`;

                Swal.fire({
                    title: 'Invalid Working Hours',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Fix'
                });
                return;
            }

            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                const response = await fetch('{{ route("doctor.schedule.working-hours.update") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Working Hours Saved',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    renderWorkingHours();
                } else {
                    Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            }
        };

        window.saveBreakTime = async function () {
            const breakStart = document.getElementById('breakStart').value;
            const breakEnd = document.getElementById('breakEnd').value;

            Swal.fire({ title: 'Updating break time...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                const response = await fetch('{{ route("doctor.schedule.break") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ break_start: breakStart, break_end: breakEnd })
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Break Time Updated',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    renderWorkingHours();
                } else {
                    Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            }
        };

        function viewAppointmentDetails(event) {
            Swal.fire({
                title: 'Appointment Details',
                html: `
                <div style="text-align: left;">
                    <p><strong>Patient:</strong> ${event.title}</p>
                    <p><strong>Time:</strong> ${new Date(event.start).toLocaleTimeString()} - ${new Date(event.end).toLocaleTimeString()}</p>
                    <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                    ${event.extendedProps.patient_notes ? `<p><strong>Patient Notes:</strong> ${event.extendedProps.patient_notes}</p>` : ''}
                </div>
            `,
                icon: 'info',
                confirmButtonColor: '#2563eb'
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initCalendar();
            renderWorkingHours();
        });
    </script>
@endpush