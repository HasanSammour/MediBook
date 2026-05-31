<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\AppointmentStatusMail;

class HospitalAppointmentController extends Controller
{
    /**
     * Display appointments page.
     */
    public function index()
    {
        $hospitalId = Auth::user()->hospital_id;

        // Get doctors list for filter dropdown
        $doctors = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return view('hospital.appointments.index', compact('doctors'));
    }

    /**
     * Get appointments data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;

        $query = Appointment::where('hospital_id', $hospitalId)
            ->with(['patient', 'doctor', 'payment'])
            ->orderBy('appointment_date', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // Filter by doctor
        if ($request->filled('doctor_id') && $request->doctor_id !== 'all') {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(10);

        // Transform data for response
        $appointments->getCollection()->transform(function ($appointment) {
            return [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->name ?? 'N/A',
                'patient_avatar_html' => $appointment->patient->avatar_html ?? null,
                'doctor_name' => $appointment->doctor->display_name ?? 'N/A',
                'doctor_avatar_html' => $appointment->doctor->avatar_html ?? null,
                'doctor_id' => $appointment->doctor_id,
                'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
                'appointment_time' => $appointment->appointment_date->format('h:i A'),
                'status' => $appointment->status,
                'status_text' => ucfirst($appointment->status),
                'has_payment' => $appointment->payment ? true : false,
                'payment_amount' => $appointment->payment ? '$' . number_format($appointment->payment->amount, 2) : null,
                'patient_notes' => $appointment->patient_notes,
                'doctor_notes' => $appointment->notes,
                'cancellation_reason' => $appointment->cancellation_reason,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $appointments->items(),
            'total' => $appointments->total(),
            'from' => $appointments->firstItem(),
            'to' => $appointments->lastItem(),
            'current_page' => $appointments->currentPage(),
            'last_page' => $appointments->lastPage(),
        ]);
    }

    /**
     * Get appointment details for modal.
     */
    public function getDetails($id)
    {
        $hospitalId = Auth::user()->hospital_id;

        $appointment = Appointment::where('hospital_id', $hospitalId)
            ->with(['patient', 'doctor', 'hospital', 'payment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'patient' => [
                    'id' => $appointment->patient->id,
                    'name' => $appointment->patient->name,
                    'email' => $appointment->patient->email,
                    'phone' => $appointment->patient->phone ?? 'N/A',
                    'gender' => ucfirst($appointment->patient->gender ?? 'Not specified'),
                    'age' => $appointment->patient->age ?? 'N/A',
                    'avatar_html' => $appointment->patient->avatar_html,
                ],
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->display_name,
                    'specialty' => $appointment->doctor->specialization ?? 'General',
                    'fee' => $appointment->doctor->consultation_fee,
                    'formatted_fee' => $appointment->doctor->formatted_fee,
                    'avatar_html' => $appointment->doctor->avatar_html,
                ],
                'hospital' => [
                    'name' => $appointment->hospital->name,
                    'address' => $appointment->hospital->address,
                    'phone' => $appointment->hospital->phone,
                ],
                'appointment_date' => $appointment->appointment_date->format('F d, Y'),
                'appointment_time' => $appointment->appointment_date->format('h:i A'),
                'status' => $appointment->status,
                'status_text' => ucfirst($appointment->status),
                'patient_notes' => $appointment->patient_notes ?? 'No notes provided',
                'doctor_notes' => $appointment->notes ?? 'No medical notes added yet',
                'cancellation_reason' => $appointment->cancellation_reason,
                'payment' => $appointment->payment ? [
                    'amount' => '$' . number_format($appointment->payment->amount, 2),
                    'method' => ucfirst($appointment->payment->payment_method),
                    'date' => $appointment->payment->payment_date->format('F d, Y h:i A'),
                    'notes' => $appointment->payment->notes,  // ← ADD THIS LINE
                ] : null,
                'is_completed' => $appointment->status === 'completed',
                'is_cancelled' => $appointment->status === 'cancelled',
                'can_record_payment' => $appointment->status === 'completed' && !$appointment->payment,
            ]
        ]);
    }

    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            $appointment = Appointment::where('hospital_id', $hospitalId)->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed',
                'cancellation_reason' => 'required_if:status,cancelled|nullable|string'
            ]);

            $oldStatus = $appointment->status;
            $newStatus = $request->status;

            $updateData = ['status' => $newStatus];

            if ($newStatus === 'cancelled' && $request->cancellation_reason) {
                $updateData['cancellation_reason'] = $request->cancellation_reason;
            }

            $appointment->update($updateData);

            // Send email notification to patient
            if ($newStatus !== $oldStatus && in_array($newStatus, ['confirmed', 'cancelled', 'completed'])) {
                try {
                    Mail::to($appointment->patient->email)->send(new AppointmentStatusMail($appointment, $newStatus));
                } catch (\Exception $e) {
                    \Log::error('Failed to send appointment status email: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export appointments to PDF.
     */
    public function export(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        $hospital = Auth::user()->hospital;

        $query = Appointment::where('hospital_id', $hospitalId)
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc');

        // Apply same filters as the list
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        if ($request->filled('doctor_id') && $request->doctor_id !== 'all') {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->get();

        $data = [
            'hospital' => $hospital,
            'appointments' => $appointments,
            'export_date' => now()->format('F d, Y h:i A'),
            'filters' => [
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'status' => $request->status,
                'doctor_id' => $request->doctor_id,
            ]
        ];

        $pdf = Pdf::loadView('hospital.appointments.pdf.appointments-report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('appointments-report-' . now()->format('Y-m-d') . '.pdf');
    }
}