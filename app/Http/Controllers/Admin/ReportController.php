<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports page.
     */
    public function index()
    {
        $years = $this->getAvailableYears();
        $currentYear = now()->year;
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        $currentMonth = now()->month;

        return view('admin.reports.index', compact('years', 'currentYear', 'months', 'currentMonth'));
    }

    /**
     * Get available years from appointments.
     */
    private function getAvailableYears()
    {
        $years = Appointment::selectRaw('YEAR(appointment_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($years)) {
            $years = [now()->year];
        }

        return $years;
    }

    /**
     * Get dashboard statistics via AJAX with year filter.
     */
    public function getStats(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', null);

            $query = Appointment::query();
            if ($year)
                $query->whereYear('appointment_date', $year);
            if ($month)
                $query->whereMonth('appointment_date', $month);

            $stats = [
                'total_hospitals' => Hospital::count(),
                'total_doctors' => User::role('doctor')->count(),
                'total_patients' => User::role('patient')->count(),
                'total_appointments' => $query->count(),
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
     * Get appointments by hospital with status breakdown.
     */
    public function getAppointmentsByHospital(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', null);

            $query = Hospital::query();

            $hospitals = $query->get();

            $labels = [];
            $pendingData = [];
            $confirmedData = [];
            $completedData = [];
            $cancelledData = [];

            foreach ($hospitals as $hospital) {
                $appointmentQuery = Appointment::where('hospital_id', $hospital->id);
                if ($year)
                    $appointmentQuery->whereYear('appointment_date', $year);
                if ($month)
                    $appointmentQuery->whereMonth('appointment_date', $month);

                $pending = (clone $appointmentQuery)->where('status', 'pending')->count();
                $confirmed = (clone $appointmentQuery)->where('status', 'confirmed')->count();
                $completed = (clone $appointmentQuery)->where('status', 'completed')->count();
                $cancelled = (clone $appointmentQuery)->where('status', 'cancelled')->count();
                $total = $pending + $confirmed + $completed + $cancelled;

                if ($total > 0) {
                    $labels[] = strlen($hospital->name) > 15 ? substr($hospital->name, 0, 15) . '...' : $hospital->name;
                    $pendingData[] = $pending;
                    $confirmedData[] = $confirmed;
                    $completedData[] = $completed;
                    $cancelledData[] = $cancelled;
                }
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'pending' => $pendingData,
                'confirmed' => $confirmedData,
                'completed' => $completedData,
                'cancelled' => $cancelledData,
            ]);

        } catch (\Exception $e) {
            \Log::error('Get appointments by hospital error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'labels' => [],
                'pending' => [],
                'confirmed' => [],
                'completed' => [],
                'cancelled' => []
            ], 500);
        }
    }

    /**
     * Get busiest doctors (top 10).
     */
    public function getBusiestDoctors(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', null);

            $query = Appointment::select(
                'users.id',
                'users.name',
                'users.specialization',
                'hospitals.name as hospital_name',
                DB::raw('COUNT(CASE WHEN appointments.status = "completed" THEN 1 END) as completed_count'),
                DB::raw('COUNT(appointments.id) as total')
            )
                ->join('users', 'appointments.doctor_id', '=', 'users.id')
                ->leftJoin('hospitals', 'users.hospital_id', '=', 'hospitals.id')
                ->where('users.is_active', true)
                ->groupBy('users.id', 'users.name', 'users.specialization', 'hospitals.name');

            if ($year)
                $query->whereYear('appointment_date', $year);
            if ($month)
                $query->whereMonth('appointment_date', $month);

            $busiestDoctors = $query->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            $labels = $busiestDoctors->pluck('name')->toArray();
            $values = $busiestDoctors->pluck('total')->toArray();

            $doctors = $busiestDoctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialty' => $doctor->specialization ?? 'General',
                    'hospital' => $doctor->hospital_name ?? 'Independent',
                    'appointments' => $doctor->total,
                    'completed' => $doctor->completed_count,
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'values' => $values,
                'doctors' => $doctors
            ]);

        } catch (\Exception $e) {
            \Log::error('Get busiest doctors error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'labels' => [],
                'values' => [],
                'doctors' => []
            ], 500);
        }
    }

    /**
     * Get monthly appointments trend for selected year.
     */
    public function getMonthlyTrend(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $values = [];

            for ($month = 1; $month <= 12; $month++) {
                $count = Appointment::whereYear('appointment_date', $year)
                    ->whereMonth('appointment_date', $month)
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
     * Export report to PDF.
     */
    public function export(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            $month = $request->get('month', null);

            $monthName = $month ? Carbon::create($year, $month, 1)->format('F') : 'Full Year';

            $appointmentQuery = Appointment::query();
            if ($year)
                $appointmentQuery->whereYear('appointment_date', $year);
            if ($month)
                $appointmentQuery->whereMonth('appointment_date', $month);

            $stats = [
                'total_hospitals' => Hospital::count(),
                'total_doctors' => User::role('doctor')->count(),
                'total_patients' => User::role('patient')->count(),
                'total_appointments' => (clone $appointmentQuery)->count(),
            ];

            // Appointments by hospital with status breakdown
            $hospitals = Hospital::all();
            $appointmentsByHospital = [];
            foreach ($hospitals as $hospital) {
                $hospitalQuery = Appointment::where('hospital_id', $hospital->id);
                if ($year)
                    $hospitalQuery->whereYear('appointment_date', $year);
                if ($month)
                    $hospitalQuery->whereMonth('appointment_date', $month);

                $pending = (clone $hospitalQuery)->where('status', 'pending')->count();
                $confirmed = (clone $hospitalQuery)->where('status', 'confirmed')->count();
                $completed = (clone $hospitalQuery)->where('status', 'completed')->count();
                $cancelled = (clone $hospitalQuery)->where('status', 'cancelled')->count();
                $total = $pending + $confirmed + $completed + $cancelled;

                if ($total > 0) {
                    $appointmentsByHospital[] = (object) [
                        'name' => $hospital->name,
                        'pending' => $pending,
                        'confirmed' => $confirmed,
                        'completed' => $completed,
                        'cancelled' => $cancelled,
                        'total' => $total
                    ];
                }
            }
            usort($appointmentsByHospital, function ($a, $b) {
                return $b->total <=> $a->total;
            });
            $appointmentsByHospital = array_slice($appointmentsByHospital, 0, 15);

            // Busiest doctors
            $busiestDoctorsQuery = Appointment::select('users.name', 'users.specialization', 'hospitals.name as hospital_name', DB::raw('count(appointments.id) as total'))
                ->join('users', 'appointments.doctor_id', '=', 'users.id')
                ->leftJoin('hospitals', 'users.hospital_id', '=', 'hospitals.id')
                ->where('users.is_active', true)
                ->groupBy('users.id', 'users.name', 'users.specialization', 'hospitals.name');

            if ($year)
                $busiestDoctorsQuery->whereYear('appointment_date', $year);
            if ($month)
                $busiestDoctorsQuery->whereMonth('appointment_date', $month);

            $busiestDoctors = $busiestDoctorsQuery->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            // Users distribution
            $usersDistribution = [
                'patients' => User::role('patient')->count(),
                'doctors' => User::role('doctor')->count(),
                'hospital_admins' => User::role('hospital_admin')->count(),
                'system_admins' => User::role('system_admin')->count(),
            ];

            // Monthly trend
            $monthlyTrend = [];
            for ($m = 1; $m <= 12; $m++) {
                $trendQuery = Appointment::whereYear('appointment_date', $year)->whereMonth('appointment_date', $m);
                $monthlyTrend[] = [
                    'month' => Carbon::create($year, $m, 1)->format('F'),
                    'count' => (clone $trendQuery)->count()
                ];
            }

            $data = [
                'year' => $year,
                'month' => $month,
                'month_name' => $monthName,
                'stats' => $stats,
                'appointments_by_hospital' => $appointmentsByHospital,
                'busiest_doctors' => $busiestDoctors,
                'users_distribution' => $usersDistribution,
                'monthly_trend' => $monthlyTrend,
                'export_date' => now()->format('F d, Y h:i A'),
            ];

            $pdf = Pdf::loadView('admin.reports.pdf.platform-report', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('platform-report-' . $year . ($month ? '-' . $month : '') . '-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Export report error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF report.');
        }
    }
}