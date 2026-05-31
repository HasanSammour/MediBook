<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class HomeController extends Controller
{
    /**
     * Show the home page with live stats and featured content
     */
    public function index()
    {
        // Get real-time statistics from database
        $stats = [
            'hospitals' => Hospital::where('is_active', true)->count(),
            'doctors' => User::role('doctor')->where('is_active', true)->count(),
            'patients' => User::role('patient')->where('is_active', true)->count(),
            'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
        ];

        // Get featured hospitals (3 hospitals with most appointments)
        $featuredHospitals = Hospital::where('is_active', true)
            ->withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->limit(3)
            ->get();

        // Get featured doctors (top 5 doctors by appointment count)
        $featuredDoctors = User::role('doctor')
            ->where('is_active', true)
            ->where('is_available', true)
            ->withCount('doctorAppointments')
            ->orderBy('doctor_appointments_count', 'desc')
            ->limit(5)
            ->get();

        // Calculate experience for each doctor
        foreach ($featuredDoctors as $doctor) {
            if ($doctor->created_at) {
                $years = floor($doctor->created_at->diffInYears(now()));
                $doctor->experience = max(3, min(15, $years + 5));
            } else {
                $doctor->experience = rand(5, 15);
            }

            $appointmentCount = $doctor->doctor_appointments_count ?? 0;
            $doctor->calculated_rating = 4.0 + min(1.0, $appointmentCount / 100);
            $doctor->calculated_rating = round($doctor->calculated_rating, 1);
        }

        // Get distinct specialties for dropdown
        $specialties = User::role('doctor')
            ->where('is_active', true)
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization')
            ->toArray();

        // Static testimonials (not from database)
        $testimonials = [
            [
                'text' => 'MediBook made finding a specialist so easy. I booked an appointment with a cardiologist within minutes. Highly recommend!',
                'name' => 'John Doe',
                'since' => '2024',
                'image' => 'images/patients/patient1.jpg',
                'initial' => 'JD'
            ],
            [
                'text' => 'The platform is user-friendly and reliable. I can easily track my appointments and medical history all in one place.',
                'name' => 'Jane Smith',
                'since' => '2024',
                'image' => 'images/patients/patient2.jpg',
                'initial' => 'JS'
            ],
            [
                'text' => 'Excellent service! The doctors are professional and the booking process is seamless. Best healthcare platform!',
                'name' => 'Robert Brown',
                'since' => '2025',
                'image' => 'images/patients/patient3.jpg',
                'initial' => 'RB'
            ]
        ];

        return view('public.index', compact(
            'stats',
            'featuredHospitals',
            'featuredDoctors',
            'specialties',
            'testimonials'
        ));
    }

    /**
     * Show the features page with real stats
     */
    public function features()
    {
        $stats = [
            'hospitals' => Hospital::where('is_active', true)->count(),
            'doctors' => User::role('doctor')->where('is_active', true)->count(),
            'patients' => User::role('patient')->where('is_active', true)->count(),
            'appointments' => Appointment::count(),
        ];

        return view('public.features', compact('stats'));
    }

    /**
     * Get distinct specialties from database for filter dropdown
     */
    private function getSpecialties()
    {
        return User::role('doctor')
            ->where('is_active', true)
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization')
            ->toArray();
    }

    /**
     * Calculate random experience (3-15 years as integer)
     */
    private function calculateExperience($doctor)
    {
        // Use created_at if available, otherwise random between 3-15
        if ($doctor->created_at) {
            $years = floor($doctor->created_at->diffInYears(now())); // Use floor() to get integer
            // Ensure between 3-15
            $experience = max(3, min(15, $years + 5));
            return (int)$experience; // Cast to integer
        }
        return rand(3, 15);
    }

    /**
     * Show the doctors page with real data from database
     */
    public function doctors(Request $request)
    {
        $query = User::role('doctor')
            ->where('is_active', true)
            ->with(['hospital', 'doctorAppointments'])
            ->withCount('doctorAppointments');

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('hospital', function($q) use ($location) {
                $q->where('name', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%");
            });
        }

        // Apply specialty filter
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

        // Add calculated experience to each doctor
        foreach ($doctors as $doctor) {
            $doctor->calculated_experience = $this->calculateExperience($doctor);
        }

        $specialties = $this->getSpecialties();

        // For AJAX requests, return JSON with HTML
        if ($request->ajax()) {
            return response()->json([
                'grid' => view('components.doctors-grid', compact('doctors'))->render(),
                'pagination' => view('components.doctors-pagination', compact('doctors'))->render(),
                'total' => $doctors->total(),
                'from' => $doctors->firstItem(),
                'to' => $doctors->lastItem()
            ]);
        }

        return view('public.doctors', compact('doctors', 'specialties'));
    }

    /**
     * Show the hospitals page with real data from database
     */
    public function hospitals(Request $request)
    {
        $query = Hospital::where('is_active', true)
            ->withCount('doctors');

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where('address', 'like', "%{$location}%");
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');

        if ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'doctors') {
            $query->orderBy('doctors_count', 'desc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $hospitals = $query->paginate(12);

        // For AJAX requests, return JSON with HTML
        if ($request->ajax()) {
            return response()->json([
                'grid' => view('components.hospitals-grid', compact('hospitals'))->render(),
                'pagination' => view('components.hospitals-pagination', compact('hospitals'))->render(),
                'total' => $hospitals->total(),
                'from' => $hospitals->firstItem(),
                'to' => $hospitals->lastItem()
            ]);
        }

        return view('public.hospitals', compact('hospitals'));
    }

    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        try {
            // Send email to admin
            Mail::to('hasansammour01@gmail.com')->send(new ContactMail($request->all()));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully! We will get back to you within 24 hours.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact email error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }

    /**
     * AJAX endpoint for doctor search on homepage (searches ALL doctors)
     */
    public function searchDoctors(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'specialty' => 'nullable|string|max:100',
        ]);

        $query = User::role('doctor')
            ->where('is_active', true)
            ->where('is_available', true)
            ->with('hospital')
            ->withCount('doctorAppointments');

        if ($request->filled('name')) {
            $name = $request->name;
            $query->where(function($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                  ->orWhere('specialization', 'like', "%{$name}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('hospital', function($q) use ($location) {
                $q->where('name', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%");
            });
        }

        if ($request->filled('specialty') && $request->specialty !== 'all' && $request->specialty !== '') {
            $query->where('specialization', $request->specialty);
        }

        $doctors = $query->limit(5)->get();

        // Calculate rating and format data
        $doctors = $doctors->map(function($doctor) {
            $appointmentCount = $doctor->doctor_appointments_count ?? 0;
            $rating = 4.0 + min(1.0, $appointmentCount / 100);

            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'display_name' => $doctor->display_name,
                'specialization' => $doctor->specialization,
                'hospital' => $doctor->hospital?->name,
                'hospital_location' => $doctor->hospital?->address,
                'consultation_fee' => $doctor->consultation_fee,
                'rating' => round($rating, 1),
                'reviews' => $appointmentCount,
                'avatar' => $doctor->avatar_url,
                'avatar_html' => $doctor->avatar_html,
                'availability' => $doctor->is_available,
            ];
        });

        return response()->json([
            'success' => true,
            'doctors' => $doctors,
        ]);
    }

    /**
     * AJAX endpoint for hospital search on homepage
     */
    public function searchHospitals(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $hospitals = Hospital::where('is_active', true)
            ->when($request->filled('q'), function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('address', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'address', 'phone', 'email']);

        return response()->json([
            'success' => true,
            'hospitals' => $hospitals,
        ]);
    }

    /**
     * Handle newsletter subscription
     */
    public function subscribeNewsletter(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            // TODO: Store email in newsletter_subscribers table when created
            // For now, just return success

            return response()->json([
                'success' => true,
                'message' => 'Subscribed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}