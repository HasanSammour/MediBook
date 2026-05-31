<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    /**
     * Display the patient dashboard landing page.
     */
    public function index()
    {
        $user = Auth::user();

        // Get dashboard statistics
        $stats = [
            'upcoming' => Appointment::where('patient_id', $user->id)
                ->where('appointment_date', '>', now())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),

            'completed' => Appointment::where('patient_id', $user->id)
                ->where('status', 'completed')
                ->count(),

            'total_spent' => Payment::whereHas('appointment', function ($query) use ($user) {
                $query->where('patient_id', $user->id);
            })->sum('amount'),

            'doctors_visited' => Appointment::where('patient_id', $user->id)
                ->where('status', 'completed')
                ->distinct('doctor_id')
                ->count('doctor_id'),
        ];

        // Get upcoming appointments (limit 5)
        $upcomingAppointments = Appointment::where('patient_id', $user->id)
            ->where('appointment_date', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['doctor', 'hospital'])
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

        // Get recent medical notes (limit 3)
        $recentMedicalNotes = Appointment::where('patient_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('notes')
            ->with(['doctor'])
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        // Get recent payments (limit 5)
        $recentPayments = Payment::whereHas('appointment', function ($query) use ($user) {
            $query->where('patient_id', $user->id);
        })
            ->with(['appointment.doctor'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'stats',
            'upcomingAppointments',
            'recentMedicalNotes',
            'recentPayments'
        ));
    }

    /**
     * Get dashboard statistics via AJAX (for live updates with loading spinner)
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'upcoming' => Appointment::where('patient_id', $user->id)
                ->where('appointment_date', '>', now())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),

            'completed' => Appointment::where('patient_id', $user->id)
                ->where('status', 'completed')
                ->count(),

            'total_spent' => Payment::whereHas('appointment', function ($query) use ($user) {
                $query->where('patient_id', $user->id);
            })->sum('amount'),

            'doctors_visited' => Appointment::where('patient_id', $user->id)
                ->where('status', 'completed')
                ->distinct('doctor_id')
                ->count('doctor_id'),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get upcoming appointments via AJAX
     */
    public function getUpcomingAppointments(Request $request)
    {
        $user = Auth::user();

        $appointments = Appointment::where('patient_id', $user->id)
            ->where('appointment_date', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['doctor', 'hospital'])
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Get recent medical notes via AJAX
     */
    public function getMedicalNotes(Request $request)
    {
        $user = Auth::user();

        $notes = Appointment::where('patient_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('notes')
            ->with(['doctor'])
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'success' => true,
            'notes' => $notes
        ]);
    }

    /**
     * Get recent payments via AJAX
     */
    public function getRecentPayments(Request $request)
    {
        $user = Auth::user();

        $payments = Payment::whereHas('appointment', function ($query) use ($user) {
            $query->where('patient_id', $user->id);
        })
            ->with(['appointment.doctor'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'payments' => $payments
        ]);
    }
}