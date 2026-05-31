<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalDashboardController extends Controller
{
    /**
     * Display hospital dashboard.
     */
    public function index()
    {
        $hospitalAdmin = Auth::user();
        $hospitalId = $hospitalAdmin->hospital_id;
        
        // Get hospital name
        $hospitalName = $hospitalAdmin->hospital ? $hospitalAdmin->hospital->name : 'Hospital';
        
        return view('hospital.dashboard', compact('hospitalName'));
    }
    
    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        
        // Total Doctors
        $totalDoctors = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->where('is_active', true)
            ->count();
        
        // Today's Appointments
        $todayAppointments = Appointment::where('hospital_id', $hospitalId)
            ->whereDate('appointment_date', today())
            ->count();
        
        // Monthly Revenue (current month)
        $monthlyRevenue = Payment::whereHas('appointment', function($query) use ($hospitalId) {
            $query->where('hospital_id', $hospitalId)
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year);
        })->sum('amount');
        
        // Total Patients (unique patients who had appointments at this hospital)
        $totalPatients = Appointment::where('hospital_id', $hospitalId)
            ->distinct('patient_id')
            ->count('patient_id');
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_doctors' => $totalDoctors,
                'today_appointments' => $todayAppointments,
                'monthly_revenue' => $monthlyRevenue,
                'total_patients' => $totalPatients,
            ]
        ]);
    }
    
    /**
     * Get appointments by doctor for chart.
     */
    public function getAppointmentsByDoctor(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        
        $data = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->where('is_active', true)
            ->withCount(['doctorAppointments' => function($query) {
                $query->whereMonth('appointment_date', now()->month)
                    ->whereYear('appointment_date', now()->year);
            }])
            ->orderBy('doctor_appointments_count', 'desc')
            ->limit(6)
            ->get();
        
        return response()->json([
            'success' => true,
            'labels' => $data->pluck('name'),
            'values' => $data->pluck('doctor_appointments_count'),
        ]);
    }
    
    /**
     * Get revenue trend for chart (last 6 months).
     */
    public function getRevenueTrend(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        
        $months = [];
        $revenues = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $revenue = Payment::whereHas('appointment', function($query) use ($hospitalId, $month) {
                $query->where('hospital_id', $hospitalId)
                    ->whereMonth('appointment_date', $month->month)
                    ->whereYear('appointment_date', $month->year);
            })->sum('amount');
            
            $revenues[] = $revenue;
        }
        
        return response()->json([
            'success' => true,
            'labels' => $months,
            'values' => $revenues,
        ]);
    }
    
    /**
     * Get today's appointments for the table.
     */
    public function getTodayAppointments(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        
        $appointments = Appointment::where('hospital_id', $hospitalId)
            ->whereDate('appointment_date', today())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'asc')
            ->get();
        
        $formattedAppointments = $appointments->map(function($apt) {
            return [
                'id' => $apt->id,
                'time' => $apt->appointment_date->format('h:i A'),
                'patient_name' => $apt->patient->name ?? 'N/A',
                'patient_avatar' => $apt->patient->avatar_url ?? null,
                'patient_avatar_html' => $apt->patient->avatar_html ?? null,
                'doctor_name' => $apt->doctor->name ?? 'N/A',
                'status' => $apt->status,
            ];
        });
        
        return response()->json([
            'success' => true,
            'appointments' => $formattedAppointments,
        ]);
    }
}