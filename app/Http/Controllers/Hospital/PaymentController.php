<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Display payments page.
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

        return view('hospital.payments.index', compact('doctors'));
    }

    /**
     * Get payments data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;

        $query = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })
            ->with(['appointment.patient', 'appointment.doctor'])
            ->orderBy('payment_date', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payment_method', $request->method);
        }

        // Filter by doctor
        if ($request->filled('doctor_id') && $request->doctor_id !== 'all') {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            });
        }

        // Search by patient name or receipt ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('appointment.patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(10);

        // Transform data for response
        $payments->getCollection()->transform(function ($payment) {
            return [
                'id' => $payment->id,
                'receipt_no' => 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'patient_name' => $payment->appointment->patient->name ?? 'N/A',
                'patient_avatar_html' => $payment->appointment->patient->avatar_html ?? null,
                'doctor_name' => $payment->appointment->doctor->display_name ?? 'N/A',
                'doctor_avatar_html' => $payment->appointment->doctor->avatar_html ?? null,
                'amount' => $payment->amount,
                'formatted_amount' => '$' . number_format($payment->amount, 2),
                'payment_method' => ucfirst($payment->payment_method),
                'payment_method_class' => $this->getMethodClass($payment->payment_method),
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'payment_time' => $payment->payment_date->format('h:i A'),
                'notes' => $payment->notes,
            ];
        });

        // Calculate summary statistics
        $summary = [
            'total_revenue' => $query->get()->sum('amount'),
            'total_count' => $query->get()->count(),
            'cash_total' => $query->get()->where('payment_method', 'cash')->sum('amount'),
            'card_total' => $query->get()->where('payment_method', 'card')->sum('amount'),
            'insurance_total' => $query->get()->where('payment_method', 'insurance')->sum('amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $payments->items(),
            'total' => $payments->total(),
            'from' => $payments->firstItem(),
            'to' => $payments->lastItem(),
            'current_page' => $payments->currentPage(),
            'last_page' => $payments->lastPage(),
            'summary' => $summary,
        ]);
    }

    /**
     * Get payment method class for badge styling.
     */
    private function getMethodClass($method)
    {
        switch ($method) {
            case 'cash':
                return 'method-cash';
            case 'card':
                return 'method-card';
            case 'insurance':
                return 'method-insurance';
            default:
                return '';
        }
    }

    /**
     * Get payment details for modal.
     */
    public function getDetails($id)
    {
        $hospitalId = Auth::user()->hospital_id;

        $payment = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })
            ->with(['appointment.patient', 'appointment.doctor', 'appointment.hospital'])
            ->findOrFail($id);

        $appointment = $payment->appointment;

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'receipt_no' => 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'amount' => '$' . number_format($payment->amount, 2),
                'payment_method' => ucfirst($payment->payment_method),
                'payment_date' => $payment->payment_date->format('F d, Y'),
                'payment_time' => $payment->payment_date->format('h:i A'),
                'notes' => $payment->notes,
                'patient' => [
                    'id' => $appointment->patient->id,
                    'name' => $appointment->patient->name,
                    'email' => $appointment->patient->email,
                    'phone' => $appointment->patient->phone ?? 'N/A',
                    'avatar_html' => $appointment->patient->avatar_html,
                ],
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->display_name,
                    'specialty' => $appointment->doctor->specialization ?? 'General',
                ],
                'hospital' => [
                    'name' => $appointment->hospital->name,
                    'address' => $appointment->hospital->address,
                    'phone' => $appointment->hospital->phone,
                ],
                'appointment' => [
                    'id' => $appointment->id,
                    'date' => $appointment->appointment_date->format('F d, Y'),
                    'time' => $appointment->appointment_date->format('h:i A'),
                    'status' => ucfirst($appointment->status),
                ],
            ]
        ]);
    }

    /**
     * Export payments to PDF.
     */
    public function export(Request $request)
    {
        $hospitalId = Auth::user()->hospital_id;
        $hospital = Auth::user()->hospital;

        $query = Payment::whereHas('appointment', function ($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })
            ->with(['appointment.patient', 'appointment.doctor'])
            ->orderBy('payment_date', 'desc');

        // Apply same filters
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        if ($request->filled('method') && $request->method !== 'all') {
            $query->where('payment_method', $request->method);
        }
        if ($request->filled('doctor_id') && $request->doctor_id !== 'all') {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('appointment.patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->get();

        $summary = [
            'total_revenue' => $payments->sum('amount'),
            'total_count' => $payments->count(),
            'cash_total' => $payments->where('payment_method', 'cash')->sum('amount'),
            'card_total' => $payments->where('payment_method', 'card')->sum('amount'),
            'insurance_total' => $payments->where('payment_method', 'insurance')->sum('amount'),
        ];

        $data = [
            'hospital' => $hospital,
            'payments' => $payments,
            'summary' => $summary,
            'export_date' => now()->format('F d, Y h:i A'),
            'filters' => [
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'method' => $request->method,
                'doctor_id' => $request->doctor_id,
            ]
        ];

        $pdf = Pdf::loadView('hospital.payments.pdf.payments-report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('payments-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Record payment for an appointment
     */
    public function recordPayment(Request $request, $appointmentId)
    {
        try {
            $hospitalId = Auth::user()->hospital_id;

            // Find the appointment and verify it belongs to this hospital
            $appointment = Appointment::where('hospital_id', $hospitalId)
                ->where('status', 'completed')
                ->findOrFail($appointmentId);

            // Check if payment already exists
            if ($appointment->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment already recorded for this appointment.'
                ], 422);
            }

            $request->validate([
                'payment_method' => 'required|in:cash,card,insurance',
                'amount' => 'required|numeric|min:0',
            ]);

            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'notes' => $request->notes ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully!',
                'payment' => $payment
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or not completed.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Record payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment: ' . $e->getMessage()
            ], 500);
        }
    }
}