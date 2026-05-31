<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== CREATE PERMISSIONS ====================
        
        // System Admin Permissions
        $systemPermissions = [
            'view_dashboard',
            'view_hospitals',
            'create_hospitals',
            'edit_hospitals',
            'delete_hospitals',
            'activate_hospitals',
            'view_all_users',
            'activate_users',
            'reset_passwords',
            'view_reports',
            'view_analytics',
        ];

        // Hospital Admin Permissions
        $hospitalPermissions = [
            'view_hospital_dashboard',
            'view_hospital_profile',
            'edit_hospital_profile',
            'view_doctors',
            'create_doctors',
            'edit_doctors',
            'delete_doctors',
            'activate_doctors',
            'view_appointment_details',
            'record_payments',
            'view_payments',
            'view_financial_reports',
            'search_patients',
            'export_data',
            'view_appointments',
        ];

        // Doctor Permissions
        $doctorPermissions = [
            'view_doctor_dashboard',
            'view_today_appointments',
            'view_all_appointments',
            'view_patient_details',
            'confirm_appointments',
            'complete_appointments',
            'add_medical_notes',
            'view_weekly_schedule',
            'update_availability',
            'view_completed_history',
            'view_appointments',
            'cancel_appointments',
        ];

        // Patient Permissions
        $patientPermissions = [
            'view_patient_dashboard',
            'register_account',
            'edit_profile',
            'change_password',
            'search_hospitals',
            'search_doctors',
            'view_doctor_profile',
            'book_appointments',
            'view_available_slots',
            'view_upcoming_appointments',
            'view_past_appointments',
            'view_booking_confirmation',
            'public_search',
            'cancel_appointments',
            'view_appointments',
        ];

        // Merge all permissions
        $allPermissions = array_merge(
            $systemPermissions,
            $hospitalPermissions,
            $doctorPermissions,
            $patientPermissions
        );

        // Create permissions (firstOrCreate prevents duplicates)
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==================== CREATE ROLES ====================

        // System Admin Role (gets all permissions)
        $systemAdminRole = Role::firstOrCreate(['name' => 'system_admin', 'guard_name' => 'web']);
        $systemAdminRole->syncPermissions($allPermissions);

        // Hospital Admin Role
        $hospitalAdminRole = Role::firstOrCreate(['name' => 'hospital_admin', 'guard_name' => 'web']);
        $hospitalAdminRole->syncPermissions($hospitalPermissions);

        // Doctor Role
        $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorRole->syncPermissions($doctorPermissions);

        // Patient Role
        $patientRole = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patientRole->syncPermissions($patientPermissions);

        // ==================== OUTPUT SUMMARY ====================
        
        $this->command->info('========================================');
        $this->command->info('Roles and Permissions Seeding Completed!');
        $this->command->info('========================================');
        $this->command->info('Roles Created:');
        $this->command->info('  - system_admin (' . count($systemPermissions) . ' permissions)');
        $this->command->info('  - hospital_admin (' . count($hospitalPermissions) . ' permissions)');
        $this->command->info('  - doctor (' . count($doctorPermissions) . ' permissions)');
        $this->command->info('  - patient (' . count($patientPermissions) . ' permissions)');
        $this->command->info('========================================');
        $this->command->info('Total unique permissions: ' . Permission::count());
        $this->command->info('========================================');
    }
}