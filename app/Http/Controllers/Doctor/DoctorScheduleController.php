<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    /**
     * Display schedule page.
     */
    public function index()
    {
        $doctor = Auth::user();
        return view('doctor.schedule.index', compact('doctor'));
    }

    /**
     * Get calendar events for FullCalendar.
     */
    public function getEvents(Request $request)
    {
        $doctorId = Auth::id();
        
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->with(['patient'])
            ->get();

        $events = [];
        foreach ($appointments as $appointment) {
            $color = match($appointment->status) {
                'confirmed' => '#10b981',
                'pending' => '#f59e0b',
                'completed' => '#3b82f6',
                default => '#6b7280'
            };

            $events[] = [
                'id' => $appointment->id,
                'title' => $appointment->patient->name ?? 'Patient',
                'start' => $appointment->appointment_date->format('Y-m-d H:i:s'),
                'end' => $appointment->appointment_date->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => $appointment->status,
                    'patient_notes' => $appointment->patient_notes,
                ]
            ];
        }

        return response()->json($events);
    }

    /**
     * Generate time slots from raw working hours with break time applied.
     * Removes slots that start DURING the break period.
     */
    private function generateTimeSlots($start, $end, $breakStart, $breakEnd)
    {
        $slots = [];
        
        // Convert to timestamps
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $breakStartTimestamp = strtotime($breakStart);
        $breakEndTimestamp = strtotime($breakEnd);
        
        $current = $startTimestamp;
        
        while ($current < $endTimestamp) {
            $slotStartTime = date('H:i', $current);
            
            // Check if slot start is DURING break period (including exact break start)
            $isDuringBreak = ($current >= $breakStartTimestamp && $current < $breakEndTimestamp);
            
            // Debug logging to see what's happening
            \Log::info('Slot check:', [
                'time' => $slotStartTime,
                'current' => $current,
                'break_start' => $breakStartTimestamp,
                'break_end' => $breakEndTimestamp,
                'is_during_break' => $isDuringBreak
            ]);
            
            if (!$isDuringBreak) {
                $slots[] = $slotStartTime;
            }
            
            $current = strtotime('+30 minutes', $current);
        }
        
        return $slots;
    }

    /**
     * Get weekly schedule via AJAX.
     */
    public function getWorkingHours(Request $request)
    {
        $doctor = Auth::user();
        
        // Get raw working hours (start/end times)
        $workingHours = $doctor->working_hours;
        if (is_string($workingHours)) {
            $workingHours = json_decode($workingHours, true);
        }
        
        if (!is_array($workingHours)) {
            $workingHours = [];
        }

        // Get current break time from session or use default
        $breakTime = session('doctor_break_time', ['start' => '12:00', 'end' => '13:00']);
        $breakStart = $breakTime['start'];
        $breakEnd = $breakTime['end'];

        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        $schedule = [];
        foreach ($days as $key => $dayName) {
            $hasHours = isset($workingHours[$key]) && isset($workingHours[$key]['enabled']) && $workingHours[$key]['enabled'] === true;
            
            if ($hasHours) {
                $start = $workingHours[$key]['start'] ?? '09:00';
                $end = $workingHours[$key]['end'] ?? '17:00';
                $hours = $start . ' - ' . $end;
            } else {
                $hours = 'Closed';
            }

            $schedule[] = [
                'day' => $dayName,
                'hours' => $hours,
                'available' => $hasHours
            ];
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'schedule' => $schedule,
                'break_time' => $breakTime
            ]);
        }

        return $schedule;
    }

    /**
     * Update working hours (stores raw start/end times).
     */
    public function updateWorkingHours(Request $request)
    {
        try {
            $doctor = Auth::user();
            
            // Get break time from request or use default
            $breakStart = $request->input('break_start', '12:00');
            $breakEnd = $request->input('break_end', '13:00');
            
            // Store break time in session
            session(['doctor_break_time' => ['start' => $breakStart, 'end' => $breakEnd]]);
            
            $workingHours = [];
            $availability = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($days as $day) {
                $enabled = $request->input($day . '_enabled');
                $isEnabled = ($enabled === 'true' || $enabled === '1' || $enabled === true);
                
                if ($isEnabled) {
                    $start = $request->input($day . '_start', '09:00');
                    $end = $request->input($day . '_end', '17:00');
                    
                    // Store raw working hours
                    $workingHours[$day] = [
                        'enabled' => true,
                        'start' => $start,
                        'end' => $end
                    ];
                    
                    // Generate slots with break time applied
                    $slots = $this->generateTimeSlots($start, $end, $breakStart, $breakEnd);
                    $availability[$day] = $slots;
                    
                } else {
                    $workingHours[$day] = [
                        'enabled' => false,
                        'start' => null,
                        'end' => null
                    ];
                    $availability[$day] = [];
                }
            }
            
            // Save raw working hours
            $doctor->working_hours = $workingHours;
            // Save generated slots for patient booking
            $doctor->availability = $availability;
            $doctor->save();

            return response()->json([
                'success' => true,
                'message' => 'Working hours updated successfully!',
                'break_time' => ['start' => $breakStart, 'end' => $breakEnd]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Update working hours error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update working hours: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update break time and regenerate all availability slots.
     */
    public function updateBreakTime(Request $request)
    {
        try {
            $request->validate([
                'break_start' => 'required|string',
                'break_end' => 'required|string',
            ]);

            $doctor = Auth::user();
            $breakStart = $request->break_start;
            $breakEnd = $request->break_end;
            
            // Get raw working hours
            $workingHours = $doctor->working_hours;
            if (is_string($workingHours)) {
                $workingHours = json_decode($workingHours, true);
            }
            
            if (!is_array($workingHours)) {
                $workingHours = [];
            }
            
            // Regenerate all availability slots from raw working hours with new break time
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $availability = [];
            
            foreach ($days as $day) {
                if (isset($workingHours[$day]) && $workingHours[$day]['enabled'] === true) {
                    $start = $workingHours[$day]['start'];
                    $end = $workingHours[$day]['end'];
                    
                    if ($start && $end) {
                        $slots = $this->generateTimeSlots($start, $end, $breakStart, $breakEnd);
                        $availability[$day] = $slots;
                    } else {
                        $availability[$day] = [];
                    }
                } else {
                    $availability[$day] = [];
                }
            }
            
            $doctor->availability = $availability;
            $doctor->save();

            // Update session break time
            session(['doctor_break_time' => ['start' => $breakStart, 'end' => $breakEnd]]);

            return response()->json([
                'success' => true,
                'message' => 'Break time updated successfully! All working hours have been adjusted.',
                'break_time' => ['start' => $breakStart, 'end' => $breakEnd]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Update break time error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update break time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle doctor availability.
     */
    public function toggleAvailability(Request $request)
    {
        try {
            $doctor = Auth::user();
            $newStatus = !$doctor->is_available;
            
            $doctor->is_available = $newStatus;
            $doctor->save();

            return response()->json([
                'success' => true,
                'is_available' => $newStatus,
                'message' => $newStatus ? 'You are now available for appointments' : 'You are now unavailable for appointments'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Toggle availability error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability'
            ], 500);
        }
    }
}