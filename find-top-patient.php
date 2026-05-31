<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "========================================\n";
echo "     FIND PATIENT WITH MOST APPOINTMENTS\n";
echo "========================================\n\n";

// Get patient with most appointments using Eloquent
$topPatient = User::role('patient')
    ->withCount('patientAppointments')
    ->orderBy('patient_appointments_count', 'desc')
    ->first();

if ($topPatient) {
    echo "🏆 TOP PATIENT:\n";
    echo "----------------------------------------\n";
    echo "Name: " . $topPatient->name . "\n";
    echo "Email: " . $topPatient->email . "\n";
    echo "Phone: " . ($topPatient->phone ?? 'Not provided') . "\n";
    echo "Total Appointments: " . $topPatient->patient_appointments_count . "\n";
    echo "Since: " . $topPatient->created_at->format('F d, Y') . "\n";
    echo "----------------------------------------\n\n";
} else {
    echo "❌ No patients found in database.\n\n";
}

// Get top 10 patients with most appointments
echo "📊 TOP 10 PATIENTS BY APPOINTMENT COUNT:\n";
echo "========================================\n";

$topPatients = User::role('patient')
    ->withCount('patientAppointments')
    ->orderBy('patient_appointments_count', 'desc')
    ->limit(10)
    ->get();

if ($topPatients->count() > 0) {
    echo sprintf("%-3s | %-25s | %-30s | %-10s\n", "#", "Name", "Email", "Appointments");
    echo str_repeat("-", 80) . "\n";
    
    $rank = 1;
    foreach ($topPatients as $patient) {
        echo sprintf("%-3s | %-25s | %-30s | %-10s\n", 
            $rank, 
            substr($patient->name, 0, 25), 
            substr($patient->email, 0, 30), 
            $patient->patient_appointments_count
        );
        $rank++;
    }
} else {
    echo "No patients found.\n";
}

echo "\n";

// Get appointment count by status for top patient
if ($topPatient) {
    echo "📋 APPOINTMENT BREAKDOWN FOR TOP PATIENT:\n";
    echo "========================================\n";
    
    $statusCounts = Appointment::where('patient_id', $topPatient->id)
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();
    
    echo sprintf("%-15s | %-10s\n", "Status", "Count");
    echo str_repeat("-", 30) . "\n";
    
    $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    foreach ($statuses as $status) {
        $count = $statusCounts->where('status', $status)->first();
        $countValue = $count ? $count->total : 0;
        echo sprintf("%-15s | %-10s\n", ucfirst($status), $countValue);
    }
    
    echo "\n";
}

// Summary
echo "📈 SUMMARY STATISTICS:\n";
echo "========================================\n";
echo "Total Patients: " . User::role('patient')->count() . "\n";
echo "Total Appointments: " . Appointment::count() . "\n";
echo "Average Appointments per Patient: " . round(Appointment::count() / max(1, User::role('patient')->count()), 2) . "\n";
echo "========================================\n\n";