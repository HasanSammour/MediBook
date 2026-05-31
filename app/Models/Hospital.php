<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function doctors()
    {
        return $this->hasMany(User::class)->role('doctor');
    }

    public function hospitalAdmins()
    {
        return $this->hasMany(User::class)->role('hospital_admin');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =========== HELPERS ===========
    /**
     * Get hospital logo URL with fallback
     */
    public function getLogoUrlAttribute()
    {
        if (empty($this->logo)) {
            return null;
        }

        // Check if file exists in public path
        $fullPath = public_path($this->logo);
        if (file_exists($fullPath)) {
            return asset($this->logo);
        }

        // Try alternative path (without 'images/' prefix)
        $altPath = str_replace('images/', '', $this->logo);
        if (file_exists(public_path($altPath))) {
            return asset($altPath);
        }

        return null;
    }

    /**
     * Get logo for display (with fallback)
     */
    public function getLogoDisplayAttribute()
    {
        $url = $this->logo_url;
        if ($url) {
            return '<img src="' . e($url) . '" alt="' . e($this->name) . '" style="width: 100%; height: 100%; object-fit: cover;">';
        }
        return '<i class="fas fa-hospital"></i>';
    }
}