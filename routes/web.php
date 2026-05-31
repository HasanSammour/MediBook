<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Hospital\HospitalDashboardController;
use App\Http\Controllers\Hospital\DoctorController;
use App\Http\Controllers\Hospital\HospitalAppointmentController;
use App\Http\Controllers\Hospital\PaymentController;
use App\Http\Controllers\Hospital\FinancialReportController;
use App\Http\Controllers\Hospital\HospitalProfileController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorScheduleController;
use App\Http\Controllers\Doctor\PatientHistoryController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\DoctorSearchController;
use App\Http\Controllers\Patient\HospitalSearchController;
use App\Http\Controllers\Patient\MedicalHistoryController;
use App\Http\Controllers\Patient\PatientProfileController;
use App\Http\Controllers\Patient\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/hospitals', [HomeController::class, 'hospitals'])->name('hospitals');
Route::get('/doctors', [HomeController::class, 'doctors'])->name('doctors');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Public POST routes
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::post('/search-doctors', [HomeController::class, 'searchDoctors'])->name('home.search.doctors');
Route::post('/search-hospitals', [HomeController::class, 'searchHospitals'])->name('home.search.hospitals');
Route::post('/subscribe-newsletter', [HomeController::class, 'subscribeNewsletter'])->name('home.newsletter');

// ============================================
// BREEZE AUTHENTICATION ROUTES
// ============================================
// These are automatically provided by Breeze

require __DIR__ . '/auth.php';

// ============================================
// AUTHENTICATED USER ROUTES (Common)
// ============================================
Route::middleware('auth', 'verified')->group(function () {
    // Profile management (provided by Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// ROLE-BASED DASHBOARD REDIRECT
// ============================================
// Override Breeze's default dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->hasRole('system_admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hospital_admin')) {
        return redirect()->route('hospital.dashboard');
    } elseif ($user->hasRole('doctor')) {
        return redirect()->route('doctor.dashboard');
    } elseif ($user->hasRole('patient')) {
        return redirect()->route('patient.dashboard');
    }

    return redirect()->route('home');
})->name('dashboard');

// ============================================
// SYSTEM ADMIN ROUTES
// ============================================
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:system_admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/appointments-by-hospital', [AdminDashboardController::class, 'getAppointmentsByHospital'])->name('dashboard.appointments-by-hospital');
        Route::get('/dashboard/users-distribution', [AdminDashboardController::class, 'getUsersDistribution'])->name('dashboard.users-distribution');
        Route::get('/dashboard/monthly-trend', [AdminDashboardController::class, 'getMonthlyTrend'])->name('dashboard.monthly-trend');
        Route::get('/dashboard/recent-hospitals', [AdminDashboardController::class, 'getRecentHospitals'])->name('dashboard.recent-hospitals');

        // Hospital Management
        Route::patch('/hospitals/{hospital}/toggle-status', [AdminHospitalController::class, 'toggleStatus'])->name('hospitals.toggle-status');
        Route::get('/hospitals-list/data', [AdminHospitalController::class, 'getData'])->name('hospitals.data');
        Route::get('/hospitals/trash', [AdminHospitalController::class, 'trash'])->name('hospitals.trash');
        Route::post('/hospitals/{id}/restore', [AdminHospitalController::class, 'restore'])->name('hospitals.restore');
        Route::delete('/hospitals/{id}/force-delete', [AdminHospitalController::class, 'forceDelete'])->name('hospitals.force-delete');
        // Separate route for logo upload
        Route::post('/hospitals/{hospital}/upload-logo', [AdminHospitalController::class, 'uploadLogo'])->name('hospitals.upload-logo');

        Route::resource('hospitals', AdminHospitalController::class);

        // User Management
        Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('/users-list/data', [AdminUserController::class, 'getData'])->name('users.data');
        Route::get('/users/trash', [AdminUserController::class, 'trash'])->name('users.trash');
        Route::post('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
        Route::post('/users/{user}/upload-avatar', [AdminUserController::class, 'uploadAvatar'])->name('users.upload-avatar');

        Route::resource('users', AdminUserController::class);

        // Reports & Analytics
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
        Route::get('/reports/stats', [AdminReportController::class, 'getStats'])->name('reports.stats');
        Route::get('/reports/appointments-by-hospital', [AdminReportController::class, 'getAppointmentsByHospital'])->name('reports.appointments-by-hospital');
        Route::get('/reports/busiest-doctors', [AdminReportController::class, 'getBusiestDoctors'])->name('reports.busiest-doctors');
        Route::get('/reports/monthly-trend', [AdminReportController::class, 'getMonthlyTrend'])->name('reports.monthly-trend');
        Route::get('/reports/users-distribution', [AdminReportController::class, 'getUsersDistribution'])->name('reports.users-distribution');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');

        // Admin Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/profile', [AdminSettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::put('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/avatar', [AdminSettingsController::class, 'uploadAvatar'])->name('settings.avatar');
    });

// ============================================
// HOSPITAL ADMIN ROUTES
// ============================================
Route::prefix('hospital')
    ->middleware(['auth', 'verified', 'role:hospital_admin'])
    ->name('hospital.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [HospitalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [HospitalDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/appointments-by-doctor', [HospitalDashboardController::class, 'getAppointmentsByDoctor'])->name('dashboard.appointments-by-doctor');
        Route::get('/dashboard/revenue-trend', [HospitalDashboardController::class, 'getRevenueTrend'])->name('dashboard.revenue-trend');
        Route::get('/dashboard/today-appointments', [HospitalDashboardController::class, 'getTodayAppointments'])->name('dashboard.today-appointments');

        // Doctor Management
        Route::resource('doctors', DoctorController::class);
        Route::get('/doctors-list/data', [DoctorController::class, 'getData'])->name('doctors.data');
        Route::patch('/doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
        Route::post('/doctors/{doctor}/reset-password', [DoctorController::class, 'resetPassword'])->name('doctors.reset-password');
        Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
        Route::get('/doctors-trash', [DoctorController::class, 'trash'])->name('doctors.trash');
        Route::post('/doctors/{id}/restore', [DoctorController::class, 'restore'])->name('doctors.restore');
        Route::delete('/doctors/{id}/force-delete', [DoctorController::class, 'forceDelete'])->name('doctors.force-delete');

        // Appointment Management
        Route::get('/appointments-list/data', [HospitalAppointmentController::class, 'getData'])->name('appointments.data');
        Route::get('/appointments/{appointment}/details', [HospitalAppointmentController::class, 'getDetails'])->name('appointments.details');
        Route::patch('/appointments/{appointment}/status', [HospitalAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::get('/appointments/export', [HospitalAppointmentController::class, 'export'])->name('appointments.export');

        Route::resource('appointments', HospitalAppointmentController::class);

        // Payment Management
        Route::get('/payments-list/data', [PaymentController::class, 'getData'])->name('payments.data');
        Route::get('/payments/{payment}/details', [PaymentController::class, 'getDetails'])->name('payments.details');
        Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');
        // Payment route for appointment page
        Route::post('/payments/{appointment}/record', [PaymentController::class, 'recordPayment'])->name('payments.record');

        Route::resource('payments', PaymentController::class);

        // Financial Reports
        Route::get('/financial-reports', [FinancialReportController::class, 'index'])->name('financial-reports');
        Route::get('/financial-reports/data', [FinancialReportController::class, 'getData'])->name('financial-reports.data');
        Route::get('/financial-reports/export', [FinancialReportController::class, 'export'])->name('financial-reports.export');

        // Hospital Profile
        Route::get('/profile', [HospitalProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [HospitalProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [HospitalProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/logo', [HospitalProfileController::class, 'updateLogo'])->name('profile.logo');

        // Hospital Profile AJAX Routes
        Route::get('/profile/stats', [HospitalProfileController::class, 'getStats'])->name('profile.stats');
        Route::get('/profile/info', [HospitalProfileController::class, 'getInfo'])->name('profile.info');
        Route::get('/profile/recent-doctors', [HospitalProfileController::class, 'getRecentDoctors'])->name('profile.recent-doctors');
        Route::post('/profile/logo', [HospitalProfileController::class, 'updateLogo'])->name('profile.logo');
    });

// ============================================
// DOCTOR ROUTES
// ============================================
Route::prefix('doctor')
    ->middleware(['auth', 'verified', 'role:doctor'])
    ->name('doctor.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DoctorDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/appointments/today', [DoctorDashboardController::class, 'getTodayAppointments'])->name('dashboard.appointments.today');

        // Appointment routes
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/data', [DoctorAppointmentController::class, 'getData'])->name('appointments.data');
        Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::patch('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('appointments.complete');
        Route::post('/appointments/{appointment}/notes', [DoctorAppointmentController::class, 'addNotes'])->name('appointments.notes');

        // Schedule Management
        Route::get('/schedule', [DoctorScheduleController::class, 'index'])->name('schedule');
        Route::get('/schedule/events', [DoctorScheduleController::class, 'getEvents'])->name('schedule.events');
        Route::get('/schedule/working-hours', [DoctorScheduleController::class, 'getWorkingHours'])->name('schedule.working-hours');
        Route::post('/schedule/working-hours', [DoctorScheduleController::class, 'updateWorkingHours'])->name('schedule.working-hours.update');
        Route::post('/schedule/break', [DoctorScheduleController::class, 'updateBreakTime'])->name('schedule.break');
        Route::patch('/schedule/availability', [DoctorScheduleController::class, 'toggleAvailability'])->name('schedule.availability');

        // Patient History
        Route::get('/patients', [PatientHistoryController::class, 'index'])->name('patients.index');
        Route::get('/patients/data', [PatientHistoryController::class, 'getData'])->name('patients.data');
        Route::get('/patients/{patient}/history', [PatientHistoryController::class, 'show'])->name('patients.show');

        // Doctor Profile
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/personal', [DoctorProfileController::class, 'updatePersonal'])->name('profile.personal');
        Route::put('/profile/professional', [DoctorProfileController::class, 'updateProfessional'])->name('profile.professional');
        Route::put('/profile/password', [DoctorProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/photo', [DoctorProfileController::class, 'uploadPhoto'])->name('profile.photo');
        Route::delete('/profile', [DoctorProfileController::class, 'destroy'])->name('profile.destroy');
    });

// ============================================
// PATIENT ROUTES
// ============================================
Route::prefix('patient')
    ->middleware(['auth', 'verified', 'role:patient'])
    ->name('patient.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [PatientDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/appointments/upcoming', [PatientDashboardController::class, 'getUpcomingAppointments'])->name('dashboard.appointments.upcoming');
        Route::get('/dashboard/medical-notes', [PatientDashboardController::class, 'getMedicalNotes'])->name('dashboard.medical-notes');
        Route::get('/dashboard/payments/recent', [PatientDashboardController::class, 'getRecentPayments'])->name('dashboard.payments.recent');

        // Appointment Management
        Route::resource('appointments', PatientAppointmentController::class);
        Route::get('/appointments-list/data', [PatientAppointmentController::class, 'getData'])->name('appointments.data');
        Route::get('/appointments/{appointment}/details', [PatientAppointmentController::class, 'getDetails'])->name('appointments.details');
        Route::patch('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');

        // Doctor Search & Booking
        Route::get('/search-doctors', [DoctorSearchController::class, 'index'])->name('search-doctors');
        Route::get('/search-doctors/data', [DoctorSearchController::class, 'getData'])->name('search-doctors.data');
        Route::get('/doctors/{doctor}/available-slots', [DoctorSearchController::class, 'getAvailableSlots'])->name('doctors.available-slots');
        Route::get('/doctors/{doctor}/profile', [DoctorSearchController::class, 'showDoctor'])->name('doctors.show');

        // Booking flow
        Route::get('/book/{doctor}', [BookingController::class, 'create'])->name('book.create');
        Route::post('/book/store', [BookingController::class, 'store'])->name('book.store');

        // Hospital Search
        Route::get('/search-hospitals', [HospitalSearchController::class, 'index'])->name('search-hospitals');
        Route::get('/search-hospitals/data', [HospitalSearchController::class, 'getData'])->name('search-hospitals.data');
        Route::get('/hospitals/{hospital}/details', [HospitalSearchController::class, 'showHospital'])->name('hospitals.show');

        // Medical History (Past Appointments)
        Route::get('/medical-history', [MedicalHistoryController::class, 'index'])->name('medical-history');
        Route::get('/medical-history/data', [MedicalHistoryController::class, 'getData'])->name('medical-history.data');
        Route::get('/medical-history/{appointment}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');

        // Patient Profile
        Route::get('/profile', [PatientProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [PatientProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [PatientProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/photo', [PatientProfileController::class, 'uploadPhoto'])->name('profile.photo');
        Route::delete('/profile', [PatientProfileController::class, 'destroy'])->name('profile.destroy');
    });