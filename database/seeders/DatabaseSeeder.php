<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Order matters due to foreign key constraints
        $this->call(RoleAndPermissionSeeder::class);  // 1. First: Create roles & permissions
        $this->call(HospitalSeeder::class);           // 2. Second: Create hospitals (15 hospitals)
        $this->call(UserSeeder::class);               // 3. Third: Create users (1 admin + 15 hospital admins + 50 doctors + 400+ patients)
        $this->call(AppointmentSeeder::class);        // 4. Fourth: Create appointments (550 appointments)
        $this->call(PaymentSeeder::class);            // 5. Fifth: Create payments (for all completed appointments)

        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('     MEDIBOOK DATABASE SEEDING COMPLETED      ');
        $this->command->info('==============================================');
        $this->command->info('');
        $this->command->info('📊 FINAL COUNTS:');
        $this->command->info('   - Hospitals: ' . \App\Models\Hospital::count());
        $this->command->info('   - Users: ' . \App\Models\User::count());
        $this->command->info('   - Appointments: ' . \App\Models\Appointment::count());
        $this->command->info('   - Payments: ' . \App\Models\Payment::count());
        $this->command->info('');
        $this->command->info('🔑 LOGIN CREDENTIALS:');
        $this->command->info('   System Admin: admin@system.com / password');
        $this->command->info('   Hospital Admin: any hospital admin email / password');
        $this->command->info('   Doctor: any doctor email / password');
        $this->command->info('   Patient: any patient email / password');
        $this->command->info('==============================================');
    }
}