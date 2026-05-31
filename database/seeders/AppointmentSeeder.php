<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $doctors = User::role('doctor')->get();
        $patients = User::role('patient')->get();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->warn('No doctors or patients found. Run UserSeeder first.');
            return;
        }

        $appointments = [];
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        // Create 500+ appointments (mix of upcoming, past, cancelled)
        for ($i = 0; $i < 550; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();

            // Random date between 60 days ago and 60 days from now
            $daysOffset = rand(-60, 60);
            $daysAhead = $daysOffset >= 0 ? $daysOffset : $daysOffset;
            $hour = rand(8, 17); // 8 AM to 5 PM
            $minute = rand(0, 1) * 30; // 0 or 30 minutes

            // Determine status based on date
            $appointmentDate = Carbon::now()->addDays($daysAhead);

            if ($appointmentDate->lt(Carbon::now())) {
                // Past appointment: completed or cancelled
                $status = rand(1, 10) <= 8 ? 'completed' : 'cancelled';
            } else {
                // Future appointment: pending or confirmed
                $status = rand(1, 10) <= 6 ? 'pending' : 'confirmed';
            }

            $cancellationReason = null;
            if ($status == 'cancelled') {
                $reasons = [
                    'Patient cancelled due to work conflict',
                    'Doctor rescheduled due to emergency',
                    'Patient no-show',
                    'Moved to different date',
                    'Personal emergency',
                    'Insurance issue',
                    'Weather conditions',
                ];
                $cancellationReason = $reasons[array_rand($reasons)];
            }

            $notes = null;
            if ($status == 'completed' && rand(1, 3) == 1) {
                $notes = $faker->sentence(rand(5, 15));
            }

            $appointments[] = [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'hospital_id' => $doctor->hospital_id,
                'appointment_date' => Carbon::parse($appointmentDate)->setTime($hour, $minute),
                'status' => $status,
                'notes' => $notes,
                'cancellation_reason' => $cancellationReason,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert appointments in chunks for performance
        foreach (array_chunk($appointments, 100) as $chunk) {
            Appointment::insert($chunk);
        }

        $this->command->info('====================================');
        $this->command->info('Appointments seeded: ' . count($appointments));
        $this->command->info('Completed: ' . Appointment::where('status', 'completed')->count());
        $this->command->info('Pending: ' . Appointment::where('status', 'pending')->count());
        $this->command->info('Confirmed: ' . Appointment::where('status', 'confirmed')->count());
        $this->command->info('Cancelled: ' . Appointment::where('status', 'cancelled')->count());
        $this->command->info('====================================');
    }
}