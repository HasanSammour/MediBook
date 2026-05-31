<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorSearchController extends Controller
{
    /**
     * Display find doctors page.
     */
    public function index()
    {
        $specialties = User::role('doctor')
            ->where('is_active', true)
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization')
            ->toArray();

        return view('patient.search.doctors', compact('specialties'));
    }

    /**
     * Get doctors data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $query = User::role('doctor')
            ->where('is_active', true)
            ->where('is_available', true)
            ->with(['hospital', 'doctorAppointments'])
            ->withCount('doctorAppointments');

        // Filter by search (name or specialty)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Filter by location/hospital
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('hospital', function ($q) use ($location) {
                $q->where('name', 'like', "%{$location}%")
                    ->orWhere('address', 'like', "%{$location}%");
            });
        }

        // Filter by specialty
        if ($request->filled('specialty') && $request->specialty !== 'all') {
            $query->where('specialization', $request->specialty);
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        if ($sort === 'fee_asc') {
            $query->orderBy('consultation_fee', 'asc');
        } elseif ($sort === 'fee_desc') {
            $query->orderBy('consultation_fee', 'desc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $doctors = $query->paginate(12);

        // Calculate experience and rating for each doctor
        $doctors->getCollection()->transform(function ($doctor) {
            // Calculate experience from created_at
            if ($doctor->created_at) {
                $years = floor($doctor->created_at->diffInYears(now()));
                $doctor->experience = max(3, min(15, $years + 5));
            } else {
                $doctor->experience = rand(5, 15);
            }

            // Calculate rating based on appointment count
            $appointmentCount = $doctor->doctor_appointments_count ?? 0;
            $doctor->calculated_rating = 4.0 + min(1.0, $appointmentCount / 100);
            $doctor->calculated_rating = round($doctor->calculated_rating, 1);

            $doctor->avatar_html = $doctor->avatar_html;

            return $doctor;
        });

        return response()->json([
            'success' => true,
            'data' => $doctors->items(),
            'total' => $doctors->total(),
            'from' => $doctors->firstItem(),
            'to' => $doctors->lastItem(),
            'current_page' => $doctors->currentPage(),
            'last_page' => $doctors->lastPage(),
        ]);
    }

    /**
     * Get available time slots for a doctor based on their working hours.
     */
    public function getAvailableSlots($doctorId, Request $request)
    {
        $doctor = User::role('doctor')->findOrFail($doctorId);

        $date = $request->get('date', now()->format('Y-m-d'));
        $dayOfWeek = strtolower(date('l', strtotime($date))); // monday, tuesday, etc.

        // Get doctor's working hours from availability JSON
        $availability = $doctor->availability;
        if (is_string($availability)) {
            $availability = json_decode($availability, true);
        }

        if (!is_array($availability)) {
            $availability = [];
        }

        // Get working slots for this day
        $workingSlots = $availability[$dayOfWeek] ?? [];

        // If no working hours set, return empty (doctor not working this day)
        if (empty($workingSlots)) {
            return response()->json([
                'success' => true,
                'slots' => [],
                'message' => 'Doctor is not working on this day.'
            ]);
        }

        // Get booked appointments for this doctor on the selected date
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->pluck('appointment_date')
            ->map(function ($datetime) {
                return $datetime->format('H:i');
            })
            ->toArray();

        // Build available slots based on doctor's working hours
        // Filter out past time slots for today

        // Get current time for comparison
        $now = Carbon::now();
        $selectedDateObj = Carbon::parse($date);
        $isToday = $selectedDateObj->isToday();

        // Get current time in H:i format for today comparison
        $currentTime = $now->format('H:i');

        $allSlots = [];
        foreach ($workingSlots as $time) {
            $isBooked = in_array($time, $bookedSlots);

            // Check if time slot is in the past for today
            $isPastSlot = false;
            if ($isToday) {
                // Convert both to comparable format (e.g., "13:00" vs "15:25")
                if ($time < $currentTime) {
                    $isPastSlot = true;
                }
            }

            // Slot is available if NOT booked AND NOT past (for today)
            $isAvailable = (!$isBooked && !$isPastSlot);

            $displayTime = date("g:i A", strtotime($time));
            $allSlots[] = [
                'value' => $time,
                'display' => $displayTime,
                'available' => $isAvailable,
                'is_booked' => $isBooked,
                'is_past' => $isPastSlot
            ];
        }

        // Debug log
        \Log::info('Available slots for date: ' . $date);
        \Log::info('Is today: ' . ($isToday ? 'Yes' : 'No'));
        \Log::info('Current time: ' . $currentTime);
        \Log::info('Slots: ', $allSlots);

        return response()->json([
            'success' => true,
            'slots' => $allSlots,
            'date' => $date,
            'is_today' => $isToday,
            'current_time' => $currentTime,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'specialty' => $doctor->specialization,
                'fee' => $doctor->consultation_fee,
            ]
        ]);
    }

    /**
     * Get doctor details for view profile modal.
     */
    public function showDoctor($id)
    {
        $doctor = User::role('doctor')
            ->where('is_active', true)
            ->with(['hospital'])
            ->withCount('doctorAppointments')
            ->findOrFail($id);

        // Calculate experience
        if ($doctor->created_at) {
            $years = floor($doctor->created_at->diffInYears(now()));
            $doctor->experience = max(3, min(15, $years + 5));
        } else {
            $doctor->experience = rand(5, 15);
        }

        // Calculate rating
        $appointmentCount = $doctor->doctor_appointments_count ?? 0;
        $doctor->calculated_rating = 4.0 + min(1.0, $appointmentCount / 100);
        $doctor->calculated_rating = round($doctor->calculated_rating, 1);

        // Get availability from JSON column
        $availability = $doctor->availability;
        $availabilityText = $this->formatAvailability($availability);

        return response()->json([
            'success' => true,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'specialty' => $doctor->specialization,
                'hospital' => $doctor->hospital->name ?? 'Independent Practice',
                'fee' => $doctor->consultation_fee,
                'experience' => $doctor->experience,
                'rating' => $doctor->calculated_rating,
                'patients' => $doctor->doctor_appointments_count,
                'availability' => $availabilityText,
                'avatar_html' => $doctor->avatar_html,
            ]
        ]);
    }

    /**
     * Format availability JSON to readable text.
     */
    private function formatAvailability($availability)
    {
        if (empty($availability)) {
            return 'Monday - Friday (9:00 AM - 5:00 PM)';
        }

        $availability = is_array($availability) ? $availability : json_decode($availability, true);

        if (empty($availability)) {
            return 'Monday - Friday (9:00 AM - 5:00 PM)';
        }

        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        $workingDays = [];
        foreach ($days as $key => $dayName) {
            if (isset($availability[$key]) && !empty($availability[$key])) {
                $slots = $availability[$key];
                if (is_array($slots) && count($slots) > 0) {
                    $start = $slots[0];
                    $end = end($slots);
                    $workingDays[] = $dayName . ' (' . $start . ' - ' . $end . ')';
                }
            }
        }

        if (empty($workingDays)) {
            return 'Monday - Friday (9:00 AM - 5:00 PM)';
        }

        return implode(' • ', $workingDays);
    }
}
