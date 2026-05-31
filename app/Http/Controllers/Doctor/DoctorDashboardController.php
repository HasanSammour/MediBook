<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    /**
     * Display doctor dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        return view('doctor.dashboard', compact('user'));
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats(Request $request)
    {
        $doctorId = Auth::id();
        $today = now()->format('Y-m-d');

        // Today's appointments count
        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->count();

        // Total patients (distinct patients who had appointments with this doctor)
        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');

        // Completed appointments today
        $completedToday = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count();

        // Total appointments all time
        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();

        // Calculate rating (based on completed appointments, max 5.0)
        $completedCount = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->count();
        
        $rating = 4.0;
        if ($completedCount > 0) {
            $rating = 4.0 + min(1.0, $completedCount / 100);
            $rating = round($rating, 1);
        }

        $stats = [
            'today_appointments' => $todayAppointments,
            'total_patients' => $totalPatients,
            'completed_today' => $completedToday,
            'total_appointments' => $totalAppointments,
            'rating' => $rating,
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        }

        return $stats;
    }

    /**
     * Get today's appointments via AJAX.
     */
    public function getTodayAppointments(Request $request)
    {
        $doctorId = Auth::id();
        $today = now()->format('Y-m-d');

        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->with(['patient', 'hospital'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        // Add age and gender to each appointment's patient data
        $appointments->each(function ($apt) {
            if ($apt->patient) { 
                $apt->patient->age = $apt->patient->age ?? 'N/A';
                $apt->patient->gender = $apt->patient->gender ?? 'N/A';
            }
        });

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }
}