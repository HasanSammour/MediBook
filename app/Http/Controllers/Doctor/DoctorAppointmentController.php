<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentStatusMail;

class DoctorAppointmentController extends Controller
{
    /**
     * Display appointments page.
     */
    public function index()
    {
        return view('doctor.appointments.index');
    }
    
    /**
     * Get appointments data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $doctorId = Auth::id();
        
        $query = Appointment::where('doctor_id', $doctorId)
            ->with(['patient', 'hospital'])
            ->orderBy('appointment_date', 'desc');
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'upcoming') {
                $query->where('appointment_date', '>', now())
                    ->whereIn('status', ['pending', 'confirmed']);
            } elseif ($request->status === 'today') {
                $query->whereDate('appointment_date', today());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->paginate(5);
        
        // Transform data to include patient avatar_html
        $formattedData = $appointments->items();
        foreach ($formattedData as $appointment) {
            if ($appointment->patient) {
                $appointment->patient->avatar_html = $appointment->patient->avatar_html;
            }
        }

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
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $appointment->update(['status' => 'confirmed']);
        
        // Send email to patient
        try {
            Mail::to($appointment->patient->email)->send(new AppointmentStatusMail($appointment, 'confirmed'));
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment confirmed successfully!'
        ]);
    }
    
    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'Cancelled by doctor'
        ]);
        
        // Send email to patient
        try {
            Mail::to($appointment->patient->email)->send(new AppointmentStatusMail($appointment, 'cancelled'));
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation email: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully!'
        ]);
    }
    
    /**
     * Complete an appointment with medical notes.
     */
    public function complete(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $appointment->update([
            'status' => 'completed',
            'notes' => $request->notes,
        ]);
        
        // Send email to patient
        try {
            Mail::to($appointment->patient->email)->send(new AppointmentStatusMail($appointment, 'completed'));
        } catch (\Exception $e) {
            \Log::error('Failed to send completion email: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment marked as completed!'
        ]);
    }
    
    /**
     * Get appointment details for modal.
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $appointment->load(['patient', 'hospital']);
        
        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->name,
                'patient_phone' => $appointment->patient->phone ?? 'N/A',
                'patient_email' => $appointment->patient->email,
                'patient_gender' => $appointment->patient->gender ?? 'Not specified',
                'patient_age' => $appointment->patient->age ?? 'N/A',
                'patient_avatar' => $appointment->patient->avatar_url,
                'patient_avatar_html' => $appointment->patient->avatar_html,
                'hospital_name' => $appointment->hospital->name ?? 'N/A',
                'date' => $appointment->appointment_date->format('F d, Y'),
                'time' => $appointment->appointment_date->format('h:i A'),
                'status' => $appointment->status,
                'patient_notes' => $appointment->patient_notes ?? 'No notes provided',
                'medical_notes' => $appointment->notes ?? 'No medical notes added yet',
                'fee' => $appointment->doctor->consultation_fee ?? 0,
            ]
        ]);
    }
}