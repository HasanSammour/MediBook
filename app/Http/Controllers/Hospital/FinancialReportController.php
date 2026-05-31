<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Display financial reports page.
     */
    public function index()
    {
        $years = $this->getAvailableYears();
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        return view('hospital.financial-reports.index', compact('years', 'currentYear', 'currentMonth'));
    }

    /**
     * Get available years from payments and appointments.
     */
    private function getAvailableYears()
    {
        $hospitalId = Auth::user()->hospital_id;
        
        $paymentYears = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })->selectRaw('YEAR(payment_date) as year')->distinct()->pluck('year')->toArray();
        
        $appointmentYears = Appointment::where('hospital_id', $hospitalId)
            ->where('status', 'completed')
            ->selectRaw('YEAR(appointment_date) as year')->distinct()->pluck('year')->toArray();
        
        $years = array_unique(array_merge($paymentYears, $appointmentYears));
        sort($years);
        
        if (empty($years)) {
            $years = [now()->year];
        }
        
        return $years;
    }

    /**
     * Get all report data via AJAX.
     */
    public function getData(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Get date range
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Get payments for the selected period
        $payments = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
                $q->where('hospital_id', $hospitalId);
            })
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        // Get completed appointments for the selected period
        $completedAppointments = Appointment::where('hospital_id', $hospitalId)
            ->where('status', 'completed')
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->get();

        // Calculate statistics
        $totalRevenue = $payments->sum('amount');
        $totalAppointments = $completedAppointments->count();
        $daysInMonth = $startDate->daysInMonth;
        $avgDailyRevenue = $totalAppointments > 0 ? round($totalRevenue / $daysInMonth, 2) : 0;
        
        // Payment success rate (appointments with payment vs completed appointments)
        $appointmentsWithPayment = $payments->unique('appointment_id')->count();
        $successRate = $totalAppointments > 0 ? round(($appointmentsWithPayment / $totalAppointments) * 100, 1) : 0;

        // Revenue trend for the selected year (monthly)
        $revenueTrend = $this->getRevenueTrend($hospitalId, $year);
        
        // Payment methods distribution
        $paymentMethods = [
            'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
            'card' => $payments->where('payment_method', 'card')->sum('amount'),
            'insurance' => $payments->where('payment_method', 'insurance')->sum('amount'),
        ];

        // Top doctors by revenue for selected month
        $topDoctors = $this->getTopDoctors($hospitalId, $startDate, $endDate);

        // Previous month comparison
        $prevMonthStart = Carbon::create($year, $month, 1)->subMonth()->startOfDay();
        $prevMonthEnd = Carbon::create($year, $month, 1)->subMonth()->endOfMonth()->endOfDay();
        
        $prevPayments = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })->whereBetween('payment_date', [$prevMonthStart, $prevMonthEnd])->sum('amount');
        
        $revenueChange = $prevPayments > 0 ? round((($totalRevenue - $prevPayments) / $prevPayments) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'stats' => [
                'total_revenue' => $totalRevenue,
                'total_appointments' => $totalAppointments,
                'avg_daily_revenue' => $avgDailyRevenue,
                'success_rate' => $successRate,
                'revenue_change' => $revenueChange,
            ],
            'revenue_trend' => $revenueTrend,
            'payment_methods' => $paymentMethods,
            'top_doctors' => $topDoctors,
        ]);
    }

    /**
     * Get monthly revenue trend for the year.
     */
    private function getRevenueTrend($hospitalId, $year)
    {
        $monthlyRevenue = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
            
            $revenue = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
                    $q->where('hospital_id', $hospitalId);
                })
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount');
            
            $monthlyRevenue[] = round($revenue, 2);
        }
        
        return [
            'labels' => $months,
            'values' => $monthlyRevenue,
        ];
    }

    /**
     * Get top doctors by revenue for a period.
     */
    private function getTopDoctors($hospitalId, $startDate, $endDate)
    {
        $doctors = User::role('doctor')
            ->where('hospital_id', $hospitalId)
            ->where('is_active', true)
            ->withCount(['doctorAppointments as appointments_count' => function ($q) use ($startDate, $endDate) {
                $q->where('status', 'completed')
                    ->whereBetween('appointment_date', [$startDate, $endDate]);
            }])
            ->get();
        
        $doctorRevenue = [];
        foreach ($doctors as $doctor) {
            $revenue = Payment::whereHas('appointment', function ($q) use ($doctor, $startDate, $endDate) {
                    $q->where('doctor_id', $doctor->id)
                        ->where('status', 'completed')
                        ->whereBetween('appointment_date', [$startDate, $endDate]);
                })
                ->sum('amount');
            
            if ($revenue > 0 || $doctor->appointments_count > 0) {
                $doctorRevenue[] = [
                    'id' => $doctor->id,
                    'name' => $doctor->display_name,
                    'specialty' => $doctor->specialization ?? 'General',
                    'appointments' => $doctor->appointments_count,
                    'revenue' => round($revenue, 2),
                    'avatar_html' => $doctor->avatar_html,
                ];
            }
        }
        
        // Sort by revenue descending and take top 5
        usort($doctorRevenue, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        
        $topDoctors = array_slice($doctorRevenue, 0, 5);
        
        // Add rank
        foreach ($topDoctors as $index => $doctor) {
            $topDoctors[$index]['rank'] = $index + 1;
        }
        
        return $topDoctors;
    }

    /**
     * Export financial report to PDF.
     */
    public function export(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        $hospital = Auth::user()->hospital;
        
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        
        // Get data for PDF
        $payments = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
                $q->where('hospital_id', $hospitalId);
            })
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->with(['appointment.patient', 'appointment.doctor'])
            ->get();
        
        $completedAppointments = Appointment::where('hospital_id', $hospitalId)
            ->where('status', 'completed')
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->count();
        
        $totalRevenue = $payments->sum('amount');
        $daysInMonth = $startDate->daysInMonth;
        $avgDailyRevenue = $completedAppointments > 0 ? round($totalRevenue / $daysInMonth, 2) : 0;
        
        $appointmentsWithPayment = $payments->unique('appointment_id')->count();
        $successRate = $completedAppointments > 0 ? round(($appointmentsWithPayment / $completedAppointments) * 100, 1) : 0;
        
        // Payment methods summary
        $cashTotal = $payments->where('payment_method', 'cash')->sum('amount');
        $cardTotal = $payments->where('payment_method', 'card')->sum('amount');
        $insuranceTotal = $payments->where('payment_method', 'insurance')->sum('amount');
        
        // Top doctors
        $topDoctors = $this->getTopDoctors($hospitalId, $startDate, $endDate);
        
        $data = [
            'hospital' => $hospital,
            'year' => $year,
            'month' => $month,
            'month_name' => Carbon::create($year, $month, 1)->format('F Y'),
            'export_date' => now()->format('F d, Y h:i A'),
            'total_revenue' => $totalRevenue,
            'total_appointments' => $completedAppointments,
            'avg_daily_revenue' => $avgDailyRevenue,
            'success_rate' => $successRate,
            'cash_total' => $cashTotal,
            'card_total' => $cardTotal,
            'insurance_total' => $insuranceTotal,
            'top_doctors' => $topDoctors,
            'payments' => $payments->take(50), // Limit to 50 for PDF
        ];
        
        $pdf = Pdf::loadView('hospital.financial-reports.pdf.financial-report', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('financial-report-' . $year . '-' . $month . '.pdf');
    }
}