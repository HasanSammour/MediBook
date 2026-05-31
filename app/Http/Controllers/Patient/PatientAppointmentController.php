<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAppointmentController extends Controller
{
    /**
     * Display my appointments page.
     */
    public function index()
    {
        return view('patient.appointments.index');
    }

    /**
     * Get appointments data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = Appointment::where('patient_id', $user->id)
            ->with(['doctor', 'hospital'])
            ->orderBy('appointment_date', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'upcoming') {
                $query->where('appointment_date', '>', now())
                    ->whereIn('status', ['pending', 'confirmed']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search by doctor name, specialty, or hospital
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('doctor', function ($doctor) use ($search) {
                    $doctor->where('name', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%");
                })->orWhereHas('hospital', function ($hospital) use ($search) {
                    $hospital->where('name', 'like', "%{$search}%");
                });
            });
        }

        $appointments = $query->paginate(5);

        // Add avatar_html to each doctor in the response
        $appointments->getCollection()->transform(function ($appointment) {
            if ($appointment->doctor) {
                // Add avatar_html to doctor object
                $appointment->doctor->avatar_html = $appointment->doctor->avatar_html;
            }
            if ($appointment->hospital) {
                $appointment->hospital->logo_url = $appointment->hospital->logo_url;
            }
            return $appointment;
        });

        return response()->json([
            'success' => true,
            'data' => $appointments->items(),
            'total' => $appointments->total(),
            'from' => $appointments->firstItem(),
            'to' => $appointments->lastItem(),
            'current_page' => $appointments->currentPage(),
            'last_page' => $appointments->lastPage(),
            'pagination' => (string) $appointments->links()
        ]);
    }

    /**
     * Cancel an appointment.
     */
    public function cancel($id, Request $request)
    {
        $appointment = Appointment::where('patient_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->findOrFail($id);

        // Check if cancellation is within 24 hours
        $hoursUntilAppointment = now()->diffInHours($appointment->appointment_date, false);

        if ($hoursUntilAppointment < 24 && $hoursUntilAppointment > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel appointment within 24 hours of scheduled time.'
            ], 422);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'Cancelled by patient'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully!'
        ]);
    }

    /**
     * Get appointment details.
     */
    public function show($id)
    {
        $appointment = Appointment::where('patient_id', Auth::id())
            ->with(['doctor', 'hospital', 'payment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ]);
    }
}
