<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "\n";
echo "========================================\n";
echo "     DOCTOR INFORMATION DEBUG TOOL\n";
echo "========================================\n\n";

// Get user ID from command line or prompt
if (isset($argv[1])) {
    $userId = $argv[1];
} else {
    echo "Enter Doctor ID: ";
    $userId = trim(fgets(STDIN));
}

if (empty($userId)) {
    echo "❌ No ID provided. Exiting...\n";
    exit(1);
}

$doctor = User::with(['hospital'])->find($userId);

if (!$doctor) {
    echo "❌ User not found with ID: " . $userId . "\n";
    exit(1);
}

echo "👨‍⚕️ DOCTOR INFORMATION:\n";
echo "========================================\n";
echo "ID: " . $doctor->id . "\n";
echo "Name: " . $doctor->name . "\n";
echo "Email: " . $doctor->email . "\n";
echo "Phone: " . ($doctor->phone ?? 'Not set') . "\n";
echo "Specialization: " . ($doctor->specialization ?? 'Not set') . "\n";
echo "Consultation Fee: $" . ($doctor->consultation_fee ?? 'Not set') . "\n";
echo "Hospital: " . ($doctor->hospital->name ?? 'Independent Practice') . "\n";
echo "Gender: " . ($doctor->gender ?? 'Not set') . "\n";
echo "Date of Birth: " . ($doctor->date_of_birth ?? 'Not set') . "\n";
echo "Status: " . ($doctor->is_active ? 'Active' : 'Inactive') . "\n";
echo "Created At: " . $doctor->created_at . "\n";
echo "========================================\n\n";

// Check availability column
echo "📅 AVAILABILITY COLUMN (old format):\n";
echo "----------------------------------------\n";
$availability = $doctor->availability;
echo "Type: " . gettype($availability) . "\n";

if (is_string($availability)) {
    echo "Value (string): " . $availability . "\n";
    $decoded = json_decode($availability, true);
    echo "Decoded to array:\n";
    print_r($decoded);
} elseif (is_array($availability)) {
    echo "Value (array):\n";
    print_r($availability);
} else {
    echo "Value: " . ($availability ?? 'NULL') . "\n";
}

echo "\n";

// Check working_hours column (new column)
echo "⏰ WORKING_HOURS COLUMN (new format):\n";
echo "----------------------------------------\n";
$workingHours = $doctor->working_hours;
echo "Type: " . gettype($workingHours) . "\n";

if (is_string($workingHours)) {
    echo "Value (string): " . $workingHours . "\n";
    $decoded = json_decode($workingHours, true);
    echo "Decoded to array:\n";
    print_r($decoded);
} elseif (is_array($workingHours)) {
    echo "Value (array):\n";
    print_r($workingHours);
} else {
    echo "Value: " . ($workingHours ?? 'NULL') . "\n";
}

echo "\n";

// Summary
echo "📊 SUMMARY:\n";
echo "========================================\n";
echo "If WORKING_HOURS is empty/NULL, you need to migrate data from AVAILABILITY.\n";
echo "Run this command to copy availability to working_hours:\n";
echo "php artisan tinker\n\n";
echo '$doctor = App\Models\User::find(' . $userId . ');';
echo "\n";
echo '$doctor->working_hours = $doctor->availability;';
echo "\n";
echo '$doctor->save();';
echo "\n";
echo "========================================\n\n";