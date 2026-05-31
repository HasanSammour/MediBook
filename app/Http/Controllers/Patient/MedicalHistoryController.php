<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    /**
     * Display medical history page.
     */
    public function index()
    {
        return view('patient.medical-history.index');
    }

    /**
     * Get medical history data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = Appointment::where('patient_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])  // ← Add cancelled
            ->with(['doctor', 'hospital', 'payment'])
            ->orderBy('appointment_date', 'desc');

        // Filter by status tab
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('appointment_date', $request->year);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('appointment_date', $request->month);
        }

        // Search by doctor name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('doctor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(10);

        // Get available years for filter
        $years = Appointment::where('patient_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->selectRaw('YEAR(appointment_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json([
            'success' => true,
            'data' => $appointments->items(),
            'total' => $appointments->total(),
            'from' => $appointments->firstItem(),
            'to' => $appointments->lastItem(),
            'current_page' => $appointments->currentPage(),
            'last_page' => $appointments->lastPage(),
            'years' => $years,
        ]);
    }

    /**
     * Get appointment details for modal.
     */
    public function show(Appointment $appointment)
    {
        // Check if the appointment belongs to the authenticated user and is completed
        if ($appointment->patient_id !== Auth::id() || $appointment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'doctor_name' => $appointment->doctor->name ?? 'N/A',
                'doctor_specialty' => $appointment->doctor->specialization ?? 'General',
                'hospital_name' => $appointment->hospital->name ?? 'N/A',
                'hospital_address' => $appointment->hospital->address ?? 'N/A',
                'date' => $appointment->appointment_date->format('F d, Y'),
                'time' => $appointment->appointment_date->format('h:i A'),
                'doctor_notes' => $appointment->notes ?? 'No medical notes available.',
                'patient_notes' => $appointment->patient_notes ?? 'No notes provided.',
                'fee' => $appointment->doctor->consultation_fee ?? 0,
                'payment_amount' => $appointment->payment->amount ?? null,
                'payment_method' => $appointment->payment->payment_method ?? null,
                'payment_date' => $appointment->payment->payment_date ? $appointment->payment->payment_date->format('F d, Y') : null,
            ]
        ]);
    }
}