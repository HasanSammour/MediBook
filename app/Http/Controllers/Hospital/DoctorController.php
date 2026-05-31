<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\DoctorCredentialsMail;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display doctors list page.
     */
    public function index()
    {
        return view('hospital.doctors.index');
    }

    /**
     * Get doctors data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;

        $query = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->with(['hospital'])
            ->withCount(['doctorAppointments as appointments_count']);

        // Filter by search (name, email, specialization)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by specialization
        if ($request->filled('specialty') && $request->specialty !== 'all') {
            $query->where('specialization', $request->specialty);
        }

        // Include soft deleted for trash view
        if ($request->filled('trash') && $request->trash === 'true') {
            $query->onlyTrashed();
        }

        // Apply sorting
        $sortField = $request->get('sort_field', 'name');
        $sortDir = $request->get('sort_dir', 'asc');

        if ($sortField === 'appointments') {
            $query->orderBy('appointments_count', $sortDir);
        } elseif ($sortField === 'fee') {
            $query->orderBy('consultation_fee', $sortDir);
        } else {
            $query->orderBy($sortField, $sortDir);
        }

        $doctors = $query->paginate(10);

        // Transform data for response
        $doctors->getCollection()->transform(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'display_name' => $doctor->display_name,
                'email' => $doctor->email,
                'phone' => $doctor->phone ?? 'N/A',
                'specialization' => $doctor->specialization ?? 'Not specified',
                'consultation_fee' => $doctor->consultation_fee,
                'formatted_fee' => $doctor->formatted_fee,
                'is_active' => $doctor->is_active,
                'is_available' => $doctor->is_available,
                'appointments_count' => $doctor->appointments_count ?? 0,
                'avatar_html' => $doctor->avatar_html,
                'created_at' => $doctor->created_at ? $doctor->created_at->format('Y-m-d') : 'N/A',
                'deleted_at' => $doctor->deleted_at,
            ];
        });

        // Get unique specializations for filter dropdown
        $specialties = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization')
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $doctors->items(),
            'total' => $doctors->total(),
            'from' => $doctors->firstItem(),
            'to' => $doctors->lastItem(),
            'current_page' => $doctors->currentPage(),
            'last_page' => $doctors->lastPage(),
            'specialties' => $specialties,
        ]);
    }

    /**
     * Show create doctor form.
     */
    public function create()
    {
        $hospital = Auth::user()->hospital;
        $specialties = $this->getSpecialtiesList();
        return view('hospital.doctors.create', compact('hospital', 'specialties'));
    }

    /**
     * Generate time slots (30-minute intervals) excluding break time.
     */
    private function generateTimeSlots($start, $end, $breakStart, $breakEnd)
    {
        $slots = [];

        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $breakStartTimestamp = strtotime($breakStart);
        $breakEndTimestamp = strtotime($breakEnd);

        $current = $startTimestamp;

        while ($current < $endTimestamp) {
            $slotStartTime = date('H:i', $current);

            $isDuringBreak = ($current >= $breakStartTimestamp && $current < $breakEndTimestamp);

            if (!$isDuringBreak) {
                $slots[] = $slotStartTime;
            }

            $current = strtotime('+30 minutes', $current);
        }

        return $slots;
    }

    /**
     * Store a new doctor.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
                'specialization' => 'required|string|max:255',
                'consultation_fee' => 'required|numeric|min:0|max:1000',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $hospitalId = Auth::user()->hospital_id;
            $breakStart = '12:00';
            $breakEnd = '13:00';

            // Process working hours and generate availability slots
            $workingHours = [];
            $availability = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($days as $day) {
                $enabled = $request->input($day . '_enabled') === 'true' || $request->input($day . '_enabled') === '1' || $request->input($day . '_enabled') === true;

                if ($enabled) {
                    $start = $request->input($day . '_start', '09:00');
                    $end = $request->input($day . '_end', '17:00');

                    $workingHours[$day] = [
                        'enabled' => true,
                        'start' => $start,
                        'end' => $end
                    ];

                    $slots = $this->generateTimeSlots($start, $end, $breakStart, $breakEnd);
                    $availability[$day] = $slots;
                } else {
                    $workingHours[$day] = ['enabled' => false, 'start' => null, 'end' => null];
                    $availability[$day] = [];
                }
            }

            // Handle profile image upload
            $profileImage = null;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = 'uploads/doctors/' . $filename;
                $file->move(public_path('uploads/doctors'), $filename);
                $profileImage = $path;
            }

            // Generate random password
            $password = Str::random(10);

            // Convert arrays to JSON strings before saving
            $doctor = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make($password),
                'hospital_id' => $hospitalId,
                'specialization' => $request->specialization,
                'consultation_fee' => $request->consultation_fee,
                'profile_image' => $profileImage,
                'is_active' => true,
                'is_available' => true,
                'working_hours' => json_encode($workingHours),
                'availability' => json_encode($availability),
            ]);

            $doctor->assignRole('doctor');

            try {
                Mail::to($doctor->email)->send(new DoctorCredentialsMail($doctor, $password, false));
            } catch (\Exception $e) {
                \Log::error('Failed to send doctor credentials email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Doctor added successfully! Login credentials have been sent to ' . $doctor->email,
                'redirect' => route('hospital.doctors.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store doctor error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit doctor form.
     */
    public function edit(User $doctor)
    {
        $hospitalId = Auth::user()->hospital_id;

        if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
            abort(403, 'Unauthorized access.');
        }

        $workingHours = $doctor->working_hours;
        if (is_string($workingHours)) {
            $workingHours = json_decode($workingHours, true);
        }
        if (!is_array($workingHours)) {
            $workingHours = [];
        }

        $hospital = Auth::user()->hospital;
        $specialties = $this->getSpecialtiesList();

        return view('hospital.doctors.edit', compact('doctor', 'workingHours', 'hospital', 'specialties'));
    }

    /**
     * Update a doctor.
     */
    public function update(Request $request, User $doctor)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($doctor->id)],
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
                'specialization' => 'required|string|max:255',
                'consultation_fee' => 'required|numeric|min:0|max:1000',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'is_active' => 'required|in:0,1,true,false',
            ]);

            $breakStart = '12:00';
            $breakEnd = '13:00';

            // Process working hours and generate availability slots
            $workingHours = [];
            $availability = [];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($days as $day) {
                $enabled = $request->input($day . '_enabled') === 'true' || $request->input($day . '_enabled') === '1' || $request->input($day . '_enabled') === true;

                if ($enabled) {
                    $start = $request->input($day . '_start', '09:00');
                    $end = $request->input($day . '_end', '17:00');

                    $workingHours[$day] = [
                        'enabled' => true,
                        'start' => $start,
                        'end' => $end
                    ];

                    $slots = $this->generateTimeSlots($start, $end, $breakStart, $breakEnd);
                    $availability[$day] = $slots;
                } else {
                    $workingHours[$day] = ['enabled' => false, 'start' => null, 'end' => null];
                    $availability[$day] = [];
                }
            }

            // Handle profile image upload
            // Handle profile image - NEW IMAGE UPLOAD
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists and not a seeded default
                if ($doctor->profile_image && file_exists(public_path($doctor->profile_image)) && !str_contains($doctor->profile_image, 'images/doctors/')) {
                    unlink(public_path($doctor->profile_image));
                }

                $file = $request->file('profile_image');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $path = 'uploads/doctors/' . $filename;

                // Create directory if not exists
                if (!file_exists(public_path('uploads/doctors'))) {
                    mkdir(public_path('uploads/doctors'), 0777, true);
                }

                $file->move(public_path('uploads/doctors'), $filename);
                $doctor->profile_image = $path;
            }
            // Handle IMAGE REMOVAL
            elseif ($request->has('remove_image') && $request->remove_image == '1') {
                // Delete existing image if exists and not a seeded default
                if ($doctor->profile_image && file_exists(public_path($doctor->profile_image)) && !str_contains($doctor->profile_image, 'images/doctors/')) {
                    unlink(public_path($doctor->profile_image));
                }
                $doctor->profile_image = null;
            }
            // If no new image and no remove flag, keep existing image (do nothing)

            // Convert is_active to boolean
            $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

            // Convert arrays to JSON strings before saving
            $doctor->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'specialization' => $request->specialization,
                'consultation_fee' => $request->consultation_fee,
                'is_active' => $isActive,
                'working_hours' => json_encode($workingHours),
                'availability' => json_encode($availability),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully!',
                'redirect' => route('hospital.doctors.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update doctor error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle doctor active status.
     */
    public function toggleStatus(User $doctor)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $newStatus = !$doctor->is_active;
            $doctor->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'Doctor activated successfully!' : 'Doctor deactivated successfully!',
                'is_active' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset doctor password.
     */
    public function resetPassword(User $doctor)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $newPassword = Str::random(10);
            $doctor->update([
                'password' => Hash::make($newPassword)
            ]);

            try {
                Mail::to($doctor->email)->send(new DoctorCredentialsMail($doctor, $newPassword, true));
            } catch (\Exception $e) {
                \Log::error('Failed to send password reset email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully! New credentials have been sent to ' . $doctor->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete a doctor.
     */
    public function destroy(User $doctor)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $doctorName = $doctor->name;
            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => "Dr. {$doctorName} has been moved to trash."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft deleted doctor.
     */
    public function restore($id)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;
            $doctor = User::withTrashed()->findOrFail($id);

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $doctorName = $doctor->name;
            $doctor->restore();

            return response()->json([
                'success' => true,
                'message' => "Dr. {$doctorName} has been restored."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a doctor.
     */
    public function forceDelete($id)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;
            $doctor = User::withTrashed()->findOrFail($id);

            if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            // Delete profile image if exists
            if ($doctor->profile_image && file_exists(public_path($doctor->profile_image))) {
                unlink(public_path($doctor->profile_image));
            }

            $doctorName = $doctor->name;
            $doctor->forceDelete();

            return response()->json([
                'success' => true,
                'message' => "Dr. {$doctorName} has been permanently deleted."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show trash page.
     */
    public function trash()
    {
        return view('hospital.doctors.trash');
    }

    /**
     * Get doctor details for view modal.
     */
    public function show(User $doctor)
    {
        $hospitalId = Auth::user()->hospital_id;

        if ($doctor->hospital_id !== $hospitalId || !$doctor->hasRole('doctor')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $age = null;
        if ($doctor->date_of_birth) {
            $age = $doctor->date_of_birth->age;
        }

        $experience = 0;
        if ($doctor->created_at) {
            $years = floor($doctor->created_at->diffInYears(now()));
            $experience = max(3, min(15, $years + 5));
        }

        $appointmentsCount = $doctor->doctorAppointments()->where('status', 'completed')->count();
        $rating = 4.0 + min(1.0, $appointmentsCount / 100);
        $rating = round($rating, 1);

        $workingHours = $doctor->working_hours;
        if (is_string($workingHours)) {
            $workingHours = json_decode($workingHours, true);
        }

        $scheduleText = '';
        if (is_array($workingHours)) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $workingDays = [];
            foreach ($days as $index => $day) {
                if (isset($workingHours[$day]) && ($workingHours[$day]['enabled'] === true || $workingHours[$day]['enabled'] === 1)) {
                    $start = $workingHours[$day]['start'];
                    $end = $workingHours[$day]['end'];
                    $workingDays[] = $dayNames[$index] . ': ' . date('g:i A', strtotime($start)) . ' - ' . date('g:i A', strtotime($end));
                }
            }
            $scheduleText = !empty($workingDays) ? implode(' • ', $workingDays) : 'Not set';
        }

        return response()->json([
            'success' => true,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->display_name,
                'email' => $doctor->email,
                'phone' => $doctor->phone ?? 'N/A',
                'gender' => ucfirst($doctor->gender ?? 'Not specified'),
                'age' => $age ?? 'N/A',
                'date_of_birth' => $doctor->date_of_birth ? $doctor->date_of_birth->format('F d, Y') : 'N/A',
                'specialization' => $doctor->specialization ?? 'Not specified',
                'consultation_fee' => $doctor->consultation_fee ? '$' . number_format($doctor->consultation_fee, 2) : 'N/A',
                'experience' => $experience,
                'rating' => $rating,
                'total_patients' => $appointmentsCount,
                'is_active' => $doctor->is_active,
                'is_available' => $doctor->is_available,
                'schedule' => $scheduleText,
                'avatar_url' => $doctor->avatar_url,
                'avatar_html' => $doctor->avatar_html,
                'joined_date' => $doctor->created_at ? $doctor->created_at->format('F d, Y') : 'N/A',
            ]
        ]);
    }

    /**
     * Get all specialties list
     */
    private function getSpecialtiesList()
    {
        return [
            'Cardiology',
            'Neurology',
            'Pediatrics',
            'Orthopedics',
            'Dermatology',
            'Psychiatry',
            'Ophthalmology',
            'Dentistry',
            'Gynecology',
            'Urology',
            'Radiology',
            'Oncology',
            'Emergency Medicine',
            'Internal Medicine',
            'Endocrinology',
            'Gastroenterology',
            'Nephrology',
            'Pulmonology',
            'Rheumatology',
            'Hematology'
        ];
    }
}
