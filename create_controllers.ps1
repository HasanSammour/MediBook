# ============================================
# Create All Controller Files for MediBook
# Run this in your Laravel project root directory
# ============================================

Write-Host "Creating Controllers for MediBook..." -ForegroundColor Green

# ============================================
# Public Controllers
# ============================================
php artisan make:controller PublicController

# ============================================
# System Admin Controllers
# ============================================
php artisan make:controller Admin\AdminDashboardController
php artisan make:controller Admin\HospitalController --resource
php artisan make:controller Admin\UserController --resource
php artisan make:controller Admin\ReportController

# ============================================
# Hospital Admin Controllers
# ============================================
php artisan make:controller Hospital\HospitalDashboardController
php artisan make:controller Hospital\DoctorController --resource
php artisan make:controller Hospital\HospitalAppointmentController --resource
php artisan make:controller Hospital\PaymentController --resource
php artisan make:controller Hospital\FinancialReportController
php artisan make:controller Hospital\HospitalProfileController

# ============================================
# Doctor Controllers
# ============================================
php artisan make:controller Doctor\DoctorDashboardController
php artisan make:controller Doctor\DoctorAppointmentController --resource
php artisan make:controller Doctor\DoctorScheduleController
php artisan make:controller Doctor\PatientHistoryController
php artisan make:controller Doctor\DoctorProfileController

# ============================================
# Patient Controllers
# ============================================
php artisan make:controller Patient\PatientDashboardController
php artisan make:controller Patient\PatientAppointmentController --resource
php artisan make:controller Patient\DoctorSearchController
php artisan make:controller Patient\HospitalSearchController
php artisan make:controller Patient\MedicalHistoryController
php artisan make:controller Patient\PatientProfileController
php artisan make:controller Patient\BookingController

Write-Host "`nAll controllers created successfully!" -ForegroundColor Green
Write-Host "Total: 23 Controllers" -ForegroundColor Cyan

# Show folder structure
Write-Host "`nController Structure:" -ForegroundColor Yellow
Write-Host "app/Http/Controllers/"
Write-Host "├── PublicController.php"
Write-Host "├── Admin/"
Write-Host "│   ├── AdminDashboardController.php"
Write-Host "│   ├── HospitalController.php"
Write-Host "│   ├── UserController.php"
Write-Host "│   └── ReportController.php"
Write-Host "├── Hospital/"
Write-Host "│   ├── HospitalDashboardController.php"
Write-Host "│   ├── DoctorController.php"
Write-Host "│   ├── HospitalAppointmentController.php"
Write-Host "│   ├── PaymentController.php"
Write-Host "│   ├── FinancialReportController.php"
Write-Host "│   └── HospitalProfileController.php"
Write-Host "├── Doctor/"
Write-Host "│   ├── DoctorDashboardController.php"
Write-Host "│   ├── DoctorAppointmentController.php"
Write-Host "│   ├── DoctorScheduleController.php"
Write-Host "│   ├── PatientHistoryController.php"
Write-Host "│   └── DoctorProfileController.php"
Write-Host "└── Patient/"
Write-Host "    ├── PatientDashboardController.php"
Write-Host "    ├── PatientAppointmentController.php"
Write-Host "    ├── DoctorSearchController.php"
Write-Host "    ├── HospitalSearchController.php"
Write-Host "    ├── MedicalHistoryController.php"
Write-Host "    ├── PatientProfileController.php"
Write-Host "    └── BookingController.php"