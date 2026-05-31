<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Payment;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get completed appointments
        $completedAppointments = Appointment::where('status', 'completed')->get();

        if ($completedAppointments->isEmpty()) {
            $this->command->warn('No completed appointments found. Make sure AppointmentSeeder ran first.');
            return;
        }

        $paymentMethods = ['cash', 'card', 'insurance'];
        $payments = [];

        foreach ($completedAppointments as $appointment) {
            // Create payment for each completed appointment
            $paymentDate = $appointment->appointment_date->copy()->addHours(rand(1, 72));
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            $notes = null;
            if (rand(1, 10) == 1) {
                $notes = $faker->sentence(rand(3, 8));
            }

            $payments[] = [
                'appointment_id' => $appointment->id,
                'amount' => $appointment->doctor->consultation_fee ?? 100,
                'payment_method' => $paymentMethod,
                'payment_date' => $paymentDate,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert payments
        foreach (array_chunk($payments, 100) as $chunk) {
            Payment::insert($chunk);
        }

        $this->command->info('====================================');
        $this->command->info('Payments seeded: ' . count($payments));
        $this->command->info('Cash payments: ' . Payment::where('payment_method', 'cash')->count());
        $this->command->info('Card payments: ' . Payment::where('payment_method', 'card')->count());
        $this->command->info('Insurance payments: ' . Payment::where('payment_method', 'insurance')->count());
        $this->command->info('Total revenue: $' . Payment::sum('amount'));
        $this->command->info('====================================');
    }
}