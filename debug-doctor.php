<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Appointment;

echo "\n";
echo "========================================\n";
echo "     DOCTOR DEBUG TOOL\n";
echo "========================================\n\n";

// Get doctor ID from command line or prompt
if (isset($argv[1])) {
    $doctorId = $argv[1];
} else {
    echo "Enter Doctor ID: ";
    $doctorId = trim(fgets(STDIN));
}

if (empty($doctorId)) {
    echo "❌ No doctor ID provided. Exiting...\n";
    exit(1);
}

// Find doctor
$doctor = User::role('doctor')->with('hospital')->find($doctorId);

if (!$doctor) {
    echo "❌ Doctor not found with ID: " . $doctorId . "\n";
    exit(1);
}

echo "✅ Doctor Found!\n";
echo "========================================\n\n";

// Basic Information
echo "📋 BASIC INFORMATION:\n";
echo "----------------------------------------\n";
echo "ID: " . $doctor->id . "\n";
echo "Name: " . $doctor->name . "\n";
echo "Email: " . $doctor->email . "\n";
echo "Phone: " . ($doctor->phone ?? 'Not provided') . "\n";
echo "Specialization: " . ($doctor->specialization ?? 'Not set') . "\n";
echo "Consultation Fee: $" . ($doctor->consultation_fee ?? 'Not set') . "\n";
echo "Status: " . ($doctor->is_active ? 'Active' : 'Inactive') . "\n";
echo "Availability Status: " . ($doctor->is_available ? 'Available' : 'Unavailable') . "\n";
echo "Hospital: " . ($doctor->hospital->name ?? 'Not assigned') . "\n";
echo "Hospital Address: " . ($doctor->hospital->address ?? 'N/A') . "\n";
echo "Member Since: " . $doctor->created_at->format('Y-m-d H:i:s') . "\n";
echo "----------------------------------------\n\n";

// Availability Slots
echo "📅 AVAILABILITY SLOTS (Working Hours):\n";
echo "----------------------------------------\n";

$availability = $doctor->availability;

if (is_string($availability)) {
    $availability = json_decode($availability, true);
}

$days = [
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',
    'sunday' => 'Sunday'
];

$hasAvailability = false;
foreach ($days as $key => $dayName) {
    $slots = isset($availability[$key]) ? $availability[$key] : [];

    if (!empty($slots) && is_array($slots)) {
        $hasAvailability = true;
        $start = $slots[0];
        $end = end($slots);
        echo "  {$dayName}: {$start} - {$end} (" . count($slots) . " slots)\n";

        // Show individual slots
        echo "     Slots: " . implode(', ', $slots) . "\n";
    } else {
        echo "  {$dayName}: Closed\n";
    }
}

if (!$hasAvailability) {
    echo "  No working hours set. Using default: Monday - Friday (09:00 - 17:00)\n";
}
echo "----------------------------------------\n\n";

// Today's Available Time Slots
echo "⏰ TODAY'S AVAILABLE TIME SLOTS:\n";
echo "----------------------------------------\n";

$today = date('Y-m-d');
$bookedSlots = Appointment::where('doctor_id', $doctor->id)
    ->whereDate('appointment_date', $today)
    ->whereIn('status', ['pending', 'confirmed'])
    ->get()
    ->pluck('appointment_date')
    ->map(function ($datetime) {
        return $datetime->format('H:i');
    })
    ->toArray();

echo "Date: " . date('l, F j, Y') . "\n";
echo "Booked slots: " . (empty($bookedSlots) ? 'None' : implode(', ', $bookedSlots)) . "\n\n";

// Generate available slots (9 AM to 5 PM, 30-min intervals)
$availableSlots = [];
for ($hour = 9; $hour <= 17; $hour++) {
    for ($minute = 0; $minute < 60; $minute += 30) {
        if ($hour == 17 && $minute > 0)
            continue;
        $time = sprintf("%02d:%02d", $hour, $minute);
        $available = !in_array($time, $bookedSlots);
        $displayTime = date("g:i A", strtotime($time));

        if ($available) {
            $availableSlots[] = $displayTime;
        }
    }
}

echo "Available slots today: " . (empty($availableSlots) ? 'None' : implode(', ', $availableSlots)) . "\n";
echo "Total available: " . count($availableSlots) . " slots\n";
echo "----------------------------------------\n\n";

// Appointment Statistics
echo "📊 APPOINTMENT STATISTICS:\n";
echo "----------------------------------------\n";

$totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
$completedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();
$pendingAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count();
$confirmedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'confirmed')->count();
$cancelledAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'cancelled')->count();
$todayAppointments = Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', $today)->count();

echo "Total Appointments: " . $totalAppointments . "\n";
echo "Completed: " . $completedAppointments . "\n";
echo "Pending: " . $pendingAppointments . "\n";
echo "Confirmed: " . $confirmedAppointments . "\n";
echo "Cancelled: " . $cancelledAppointments . "\n";
echo "Today's Appointments: " . $todayAppointments . "\n";
echo "----------------------------------------\n\n";

// Calculate Rating
$rating = 4.0;
if ($completedAppointments > 0) {
    $rating = 4.0 + min(1.0, $completedAppointments / 100);
    $rating = round($rating, 1);
}
echo "⭐ Rating: " . $rating . "/5.0\n";
echo "========================================\n\n";

// JSON Output (for debugging)
echo "📝 RAW AVAILABILITY JSON:\n";
echo json_encode($availability, JSON_PRETTY_PRINT) . "\n";
echo "========================================\n\n";