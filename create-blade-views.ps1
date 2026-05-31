# ============================================
# Create All Blade View Files for MediBook
# Run this in your Laravel project root directory
# ============================================

Write-Host "Creating Blade Views for MediBook..." -ForegroundColor Green

$basePath = "resources/views/"

# ============================================
# Layouts (Will be created in Week 10/11)
# ============================================
Write-Host "Creating layout structures..." -ForegroundColor Yellow

# Create directories
New-Item -ItemType Directory -Force -Path "$basePath/layouts" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/auth" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/public" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/admin" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/hospital" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/doctor" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/patient" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath/components" | Out-Null

# Layout files (empty for now)
@(
    "layouts/app.blade.php",
    "layouts/admin.blade.php",
    "layouts/hospital.blade.php",
    "layouts/doctor.blade.php",
    "layouts/patient.blade.php",
    "layouts/guest.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# Auth layout (Breeze already has these, but we'll keep structure)
@(
    "auth/login.blade.php",
    "auth/register.blade.php",
    "auth/forgot-password.blade.php",
    "auth/reset-password.blade.php",
    "auth/verify-email.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# Public Pages (Blade versions of static HTML)
# ============================================
Write-Host "Creating public page views..." -ForegroundColor Yellow

@(
    "public/index.blade.php",
    "public/features.blade.php",
    "public/hospitals.blade.php",
    "public/doctors.blade.php",
    "public/contact.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# System Admin Views
# ============================================
Write-Host "Creating System Admin views..." -ForegroundColor Yellow

@(
    "admin/dashboard.blade.php",
    "admin/hospitals/index.blade.php",
    "admin/hospitals/create.blade.php",
    "admin/hospitals/edit.blade.php",
    "admin/hospitals/show.blade.php",
    "admin/users/index.blade.php",
    "admin/users/create.blade.php",
    "admin/users/edit.blade.php",
    "admin/users/show.blade.php",
    "admin/reports/index.blade.php",
    "admin/settings/index.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# Hospital Admin Views
# ============================================
Write-Host "Creating Hospital Admin views..." -ForegroundColor Yellow

@(
    "hospital/dashboard.blade.php",
    "hospital/doctors/index.blade.php",
    "hospital/doctors/create.blade.php",
    "hospital/doctors/edit.blade.php",
    "hospital/doctors/show.blade.php",
    "hospital/appointments/index.blade.php",
    "hospital/appointments/show.blade.php",
    "hospital/payments/index.blade.php",
    "hospital/payments/show.blade.php",
    "hospital/financial-reports/index.blade.php",
    "hospital/profile/show.blade.php",
    "hospital/profile/edit.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# Doctor Views
# ============================================
Write-Host "Creating Doctor views..." -ForegroundColor Yellow

@(
    "doctor/dashboard.blade.php",
    "doctor/appointments/index.blade.php",
    "doctor/appointments/show.blade.php",
    "doctor/schedule/index.blade.php",
    "doctor/patients/index.blade.php",
    "doctor/patients/show.blade.php",
    "doctor/profile/show.blade.php",
    "doctor/profile/edit.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# Patient Views
# ============================================
Write-Host "Creating Patient views..." -ForegroundColor Yellow

@(
    "patient/dashboard.blade.php",
    "patient/appointments/index.blade.php",
    "patient/appointments/show.blade.php",
    "patient/search/doctors.blade.php",
    "patient/search/hospitals.blade.php",
    "patient/booking/create.blade.php",
    "patient/booking/confirmation.blade.php",
    "patient/medical-history/index.blade.php",
    "patient/medical-history/show.blade.php",
    "patient/profile/show.blade.php",
    "patient/profile/edit.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

# ============================================
# Components (reusable)
# ============================================
Write-Host "Creating component views..." -ForegroundColor Yellow

@(
    "components/navbar.blade.php",
    "components/footer.blade.php",
    "components/sidebar.blade.php",
    "components/alert.blade.php",
    "components/loading-spinner.blade.php"
) | ForEach-Object { New-Item -ItemType File -Force -Path "$basePath$_" | Out-Null }

Write-Host "`nAll Blade views created successfully!" -ForegroundColor Green
Write-Host "Total: $(Get-ChildItem -Path $basePath -Recurse -File -Filter *.blade.php | Measure-Object | Select-Object -ExpandProperty Count) Blade files created" -ForegroundColor Cyan

Write-Host "`nView Structure:" -ForegroundColor Yellow
Write-Host "resources/views/"
Write-Host "├── layouts/"
Write-Host "│   ├── app.blade.php"
Write-Host "│   ├── admin.blade.php"
Write-Host "│   ├── hospital.blade.php"
Write-Host "│   ├── doctor.blade.php"
Write-Host "│   ├── patient.blade.php"
Write-Host "│   └── guest.blade.php"
Write-Host "├── auth/                      (Breeze templates)"
Write-Host "├── public/"
Write-Host "│   ├── index.blade.php"
Write-Host "│   ├── features.blade.php"
Write-Host "│   ├── hospitals.blade.php"
Write-Host "│   ├── doctors.blade.php"
Write-Host "│   └── contact.blade.php"
Write-Host "├── admin/"
Write-Host "│   ├── dashboard.blade.php"
Write-Host "│   ├── hospitals/"
Write-Host "│   ├── users/"
Write-Host "│   ├── reports/"
Write-Host "│   └── settings/"
Write-Host "├── hospital/"
Write-Host "│   ├── dashboard.blade.php"
Write-Host "│   ├── doctors/"
Write-Host "│   ├── appointments/"
Write-Host "│   ├── payments/"
Write-Host "│   ├── financial-reports/"
Write-Host "│   └── profile/"
Write-Host "├── doctor/"
Write-Host "│   ├── dashboard.blade.php"
Write-Host "│   ├── appointments/"
Write-Host "│   ├── schedule/"
Write-Host "│   ├── patients/"
Write-Host "│   └── profile/"
Write-Host "├── patient/"
Write-Host "│   ├── dashboard.blade.php"
Write-Host "│   ├── appointments/"
Write-Host "│   ├── search/"
Write-Host "│   ├── booking/"
Write-Host "│   ├── medical-history/"
Write-Host "│   └── profile/"
Write-Host "└── components/"
Write-Host "    ├── navbar.blade.php"
Write-Host "    ├── footer.blade.php"
Write-Host "    ├── sidebar.blade.php"
Write-Host "    ├── alert.blade.php"
Write-Host "    └── loading-spinner.blade.php"