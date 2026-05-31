<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientHistoryController extends Controller
{
    /**
     * Display patient history page.
     */
    public function index()
    {
        return view('doctor.patients.index');
    }

    /**
     * Get patients data via AJAX.
     */
    public function getData(Request $request)
    {
        $doctorId = Auth::id();

        // Get all patients who have had appointments with this doctor
        $patientIds = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->distinct('patient_id')
            ->pluck('patient_id');

        $query = User::whereIn('id', $patientIds)
            ->where('is_active', true);

        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $patients = $query->paginate(10);

        // Add statistics for each patient
        foreach ($patients as $patient) {
            // Get total visits count
            $patient->total_visits = Appointment::where('doctor_id', $doctorId)
                ->where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->count();

            // get age and gender for patient cards 
            $patient->gender_text = '';
            if ($patient->gender == 'male') {
                $patient->gender_text = 'Male';
            } elseif ($patient->gender == 'female') {
                $patient->gender_text = 'Female';
            } elseif ($patient->gender == 'other') {
                $patient->gender_text = 'Other';
            }
            $patient->age = $patient->age;

            // Get last visit date
            $lastVisit = Appointment::where('doctor_id', $doctorId)
                ->where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->orderBy('appointment_date', 'desc')
                ->first();

            $patient->last_visit = $lastVisit ? $lastVisit->appointment_date->format('F d, Y') : 'N/A';    
        }

        return response()->json([
            'success' => true,
            'data' => $patients->items(),
            'total' => $patients->total(),
            'from' => $patients->firstItem(),
            'to' => $patients->lastItem(),
            'current_page' => $patients->currentPage(),
            'last_page' => $patients->lastPage(),
        ]);
    }

    /**
     * Get patient medical history.
     */
    public function show(User $patient)
    {
        $doctorId = Auth::id();

        // Check if this patient has appointments with this doctor
        $hasAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasAppointments) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get all completed appointments for this patient with this doctor
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->with(['hospital'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Format appointments data
        $formattedAppointments = $appointments->map(function ($apt) {
            // Extract prescription from notes (simple extraction)
            $prescription = null;
            if ($apt->notes && preg_match('/prescription:?\s*(.*)/i', $apt->notes, $matches)) {
                $prescription = trim($matches[1]);
            }

            return [
                'id' => $apt->id,
                'date' => $apt->appointment_date->format('F d, Y'),
                'time' => $apt->appointment_date->format('h:i A'),
                'hospital' => $apt->hospital->name ?? 'N/A',
                'diagnosis' => $apt->notes ? substr($apt->notes, 0, 150) : 'No diagnosis recorded',
                'full_notes' => $apt->notes ?? 'No medical notes available',
                'prescription' => $prescription,
                'patient_notes' => $apt->patient_notes ?? 'No notes provided',
            ];
        });

        // Get gender display text
        $genderText = '';
        if ($patient->gender == 'male') {
            $genderText = 'Male';
        } elseif ($patient->gender == 'female') {
            $genderText = 'Female';
        } elseif ($patient->gender == 'other') {
            $genderText = 'Other';
        } else {
            $genderText = 'Not specified';
        }
    
        // Calculate age
        $age = $patient->age; // This uses the getAgeAttribute() method from user model

        return response()->json([
            'success' => true,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->phone ?? 'N/A',
                'gender' => $genderText,
                'age' => $age,
                'avatar_html' => $patient->avatar_html,
                'total_visits' => $appointments->count(),
                'registered_since' => $patient->created_at->format('F d, Y'),
            ],
            'appointments' => $formattedAppointments,
        ]);
    }
}