<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_hospitals' => Hospital::count(),
                'total_doctors' => User::role('doctor')->count(),
                'total_patients' => User::role('patient')->count(),
                'total_appointments' => Appointment::count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            \Log::error('Get admin stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get appointments by hospital for chart.
     */
    public function getAppointmentsByHospital()
    {
        try {
            $appointmentsByHospital = Appointment::select('hospitals.name', DB::raw('count(appointments.id) as total'))
                ->join('hospitals', 'appointments.hospital_id', '=', 'hospitals.id')
                ->groupBy('hospitals.id', 'hospitals.name')
                ->orderBy('total', 'desc')
                ->limit(15)
                ->get();

            $labels = $appointmentsByHospital->pluck('name')->toArray();
            $values = $appointmentsByHospital->pluck('total')->toArray();

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'values' => $values
            ]);

        } catch (\Exception $e) {
            \Log::error('Get appointments by hospital error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'labels' => [],
                'values' => []
            ], 500);
        }
    }

    /**
     * Get users distribution for chart.
     */
    public function getUsersDistribution()
    {
        try {
            $patients = User::role('patient')->count();
            $doctors = User::role('doctor')->count();
            $hospitalAdmins = User::role('hospital_admin')->count();
            $systemAdmins = User::role('system_admin')->count();

            return response()->json([
                'success' => true,
                'labels' => ['Patients', 'Doctors', 'Hospital Admins', 'System Admins'],
                'values' => [$patients, $doctors, $hospitalAdmins, $systemAdmins],
                'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6']
            ]);

        } catch (\Exception $e) {
            \Log::error('Get users distribution error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'labels' => [],
                'values' => []
            ], 500);
        }
    }

    /**
     * Get monthly appointments trend for chart.
     */
    public function getMonthlyTrend()
    {
        try {
            $months = [];
            $values = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->format('M Y');
                
                $count = Appointment::whereYear('appointment_date', $date->year)
                    ->whereMonth('appointment_date', $date->month)
                    ->count();
                    
                $values[] = $count;
            }

            return response()->json([
                'success' => true,
                'labels' => $months,
                'values' => $values
            ]);

        } catch (\Exception $e) {
            \Log::error('Get monthly trend error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'labels' => [],
                'values' => []
            ], 500);
        }
    }

    /**
     * Get recent hospitals for table.
     */
    public function getRecentHospitals()
    {
        try {
            $recentHospitals = Hospital::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($hospital) {
                    return [
                        'id' => $hospital->id,
                        'name' => $hospital->name,
                        'location' => $this->getCityFromAddress($hospital->address),
                        'doctors_count' => User::role('doctor')->where('hospital_id', $hospital->id)->count(),
                        'status' => $hospital->is_active ? 'active' : 'inactive',
                        'created_at' => $hospital->created_at ? $hospital->created_at->format('Y-m-d') : 'N/A',
                    ];
                });

            return response()->json([
                'success' => true,
                'hospitals' => $recentHospitals
            ]);

        } catch (\Exception $e) {
            \Log::error('Get recent hospitals error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'hospitals' => []
            ], 500);
        }
    }

    /**
     * Extract city from address (simple extraction).
     */
    private function getCityFromAddress($address)
    {
        if (empty($address)) return 'N/A';
        
        $parts = explode(',', $address);
        $city = trim(end($parts));
        
        if (strlen($city) > 30) {
            $city = substr($city, 0, 30) . '...';
        }
        
        return $city;
    }
}