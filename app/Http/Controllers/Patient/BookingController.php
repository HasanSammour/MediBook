<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\NewAppointmentNotificationMail;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show the booking form for a doctor.
     */
    public function create(User $doctor)
    {
        // Check if the user is a doctor
        if (!$doctor->hasRole('doctor')) {
            abort(404);
        }

        return view('patient.book.create', compact('doctor'));
    }

    /**
     * Store a new appointment.
     */
    public function store(Request $request)
    {
        // Debug: Log the request
        \Log::info('Booking request received', $request->all());

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',             
            'time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        $doctor = User::findOrFail($request->doctor_id);
        $patient = Auth::user();

        // Combine date and time
        $appointmentDateTime = Carbon::parse($request->appointment_date)
            ->setTimeFromTimeString($request->time);

        // Check if appointment time is in the past
        if ($appointmentDateTime->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot book an appointment in the past. Please select a future date and time.'
            ], 422);
        }

        // For today's date, check if time is after current time
        if ($appointmentDateTime->isToday() && $appointmentDateTime->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot has already passed. Please select a future time.'
            ], 422);
        }

        // Check if slot is still available
        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $appointmentDateTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is no longer available. Please select another time.'
            ], 422);
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'hospital_id' => $doctor->hospital_id,
            'appointment_date' => $appointmentDateTime,
            'status' => 'pending',
            'patient_notes' => $request->notes,
        ]);

        // Send email confirmation to patient (wrap in try-catch to avoid breaking booking)
        try {
            Mail::to($patient->email)->send(new AppointmentConfirmationMail($appointment, $patient, $doctor));
        } catch (\Exception $e) {
            \Log::error('Failed to send appointment confirmation email: ' . $e->getMessage());
        }

        // Send email notification to doctor
        try {
            Mail::to($doctor->email)->send(new NewAppointmentNotificationMail($appointment, $patient, $doctor));
        } catch (\Exception $e) {
            \Log::error('Failed to send doctor notification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully!',
            'appointment_id' => $appointment->id
        ]);
    }
}