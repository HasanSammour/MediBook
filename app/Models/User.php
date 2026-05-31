<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'gender',           
        'date_of_birth',
        'is_active',
        'hospital_id',
        'specialization',
        'consultation_fee',
        'availability',
        'working_hours',
        'is_available',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'availability' => 'array',
            'consultation_fee' => 'decimal:2',
            'date_of_birth' => 'date',
        ];
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the hospital that this user belongs to (for hospital admins and doctors)
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get appointments where this user is the patient
     */
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get appointments where this user is the doctor
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get payments through appointments (for patients)
     */
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope a query to only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only available doctors
     */
    public function scopeAvailableDoctors($query)
    {
        return $query->where('is_available', true)
                     ->where('is_active', true)
                     ->whereNotNull('specialization');
    }

    /**
     * Scope a query to only doctors
     */
    public function scopeDoctors($query)
    {
        return $query->role('doctor');
    }

    /**
     * Scope a query to only patients
     */
    public function scopePatients($query)
    {
        return $query->role('patient');
    }

    /**
     * Scope a query to only hospital admins
     */
    public function scopeHospitalAdmins($query)
    {
        return $query->role('hospital_admin');
    }

    // ============================================
    // HELPERS
    // ============================================

    /**
     * Calculate age from date of birth
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Check if user is a doctor
     */
    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    /**
     * Check if user is a patient
     */
    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    /**
     * Check if user is a hospital admin
     */
    public function isHospitalAdmin(): bool
    {
        return $this->hasRole('hospital_admin');
    }

    /**
     * Check if user is system admin
     */
    public function isSystemAdmin(): bool
    {
        return $this->hasRole('system_admin');
    }

    /**
     * Get user avatar URL with proper fallback
     */
    public function getAvatarUrlAttribute(): string
    {
        // If profile_image is set
        if ($this->profile_image && !empty($this->profile_image)) {
            // Build the full public path
            $publicPath = public_path($this->profile_image);

            // Check if file exists in public directory
            if (file_exists($publicPath)) {
                return asset($this->profile_image);
            }

            // Also try without the 'images/' prefix if it has it
            $altPath = str_replace('images/', '', $this->profile_image);
            if (file_exists(public_path($altPath))) {
                return asset($altPath);
            }
        }

        // Fallback to initials avatar (returns data:image/svg+xml)
        return $this->getInitialsAvatar();
    }

    /**
     * Generate avatar with user initials (colorful background) - Returns SVG data URL
     */
    public function getInitialsAvatar(): string
    {
        $initials = $this->getInitials();
        $colors = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];

        $hash = $this->email ? crc32($this->email) : $this->id;
        $colorIndex = abs($hash) % count($colors);
        $color = $colors[$colorIndex];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <rect width="100" height="100" fill="' . $color . '" rx="50"/>
            <text x="50" y="50" text-anchor="middle" dy=".3em" fill="white" font-size="40" font-weight="600" font-family="Arial, sans-serif">' . htmlspecialchars($initials) . '</text>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get avatar as HTML (for use in Blade) - Always returns IMG tag
     */
    public function getAvatarHtmlAttribute(): string
    {
        $avatarUrl = $this->avatar_url;
        return '<img src="' . e($avatarUrl) . '" alt="' . e($this->name) . '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
    }

    /**
     * Get user initials (first letter of first and last name)
     */
    public function getInitials(): string
    {
        $name = $this->name;
    
        // Remove "Dr." prefix if exists (both English and Arabic)
        $name = str_replace('Dr. ', '', $name);
        $name = str_replace('د.', '', $name);
        $name = trim($name);
    
        $words = explode(' ', $name);
        $initials = '';
    
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($words[0], 0, 2));
        }
    
        return $initials;
    }
    
    /**
     * Get user's role display name
     */
    public function getRoleDisplayNameAttribute(): string
    {
        $role = $this->roles->first();
        if (!$role) return 'User';
        
        return match($role->name) {
            'system_admin' => 'System Administrator',
            'hospital_admin' => 'Hospital Administrator',
            'doctor' => 'Doctor',
            'patient' => 'Patient',
            default => ucfirst(str_replace('_', ' ', $role->name))
        };
    }
    
    /**
     * Check if user has active status
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
    
    /**
     * Check if user is available (for doctors)
     */
    public function isAvailable(): bool
    {
        if (!$this->hasRole('doctor')) return false;
        return $this->is_available && $this->is_active;
    }

    /**
     * Get doctor's available time slots for a specific day
     */
    public function getAvailableSlotsForDay(string $day): array
    {
        if (!$this->isDoctor() || !$this->availability) {
            return [];
        }
        
        $availability = is_array($this->availability) 
            ? $this->availability 
            : json_decode($this->availability, true);
            
        return $availability[strtolower($day)] ?? [];
    }

    public function getAvailableSlotsForDate($date)
    {
        // Get booked slots for this doctor on the given date
        $bookedSlots = Appointment::where('doctor_id', $this->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_date')
            ->map(fn($datetime) => $datetime->format('H:i'))
            ->toArray();

        // Generate 30-minute slots from 9 AM to 5 PM
        $allSlots = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                if ($hour == 17 && $minute > 0) continue;
                $time = sprintf("%02d:%02d", $hour, $minute);
                $allSlots[] = [
                    'value' => $time,
                    'display' => date("g:i A", strtotime($time)),
                    'available' => !in_array($time, $bookedSlots)
                ];
            }
        }

        return $allSlots;
    }

    /**
     * Get user's full name with title if doctor (without duplicating Dr.)
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isDoctor()) {
            // Check if name already starts with "Dr."
            if (!str_starts_with($this->name, 'Dr.') && !str_starts_with($this->name, 'د.')) {
                return 'Dr. ' . $this->name;
            }
            return $this->name;
        }
        return $this->name;
    }

    /**
     * Get consultation fee formatted
     */
    public function getFormattedFeeAttribute(): string
    {
        if (!$this->consultation_fee) {
            return 'N/A';
        }
        return '$' . number_format($this->consultation_fee, 2);
    }
}