<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HospitalProfileController extends Controller
{
    /**
     * Display hospital profile page.
     */
    public function show()
    {
        $hospital = Auth::user()->hospital;

        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        return view('hospital.profile.show', compact('hospital'));
    }

    /**
     * Show edit hospital profile form.
     */
    public function edit()
    {
        $hospital = Auth::user()->hospital;

        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        return view('hospital.profile.edit', compact('hospital'));
    }

    /**
     * Update hospital profile.
     */
    public function update(Request $request)
    {
        try {
            $hospital = Auth::user()->hospital;

            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('hospitals')->ignore($hospital->id)],
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
            ]);

            $hospital->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Handle logo removal
            if ($request->has('remove_logo') && $request->remove_logo == '1') {
                if ($hospital->logo && file_exists(public_path($hospital->logo)) && !str_contains($hospital->logo, 'images/hospital_logo/')) {
                    unlink(public_path($hospital->logo));
                }
                $hospital->logo = null;
                $hospital->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Hospital profile updated successfully!',
                'redirect' => route('hospital.profile.show')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update hospital profile error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update hospital logo.
     */
    public function updateLogo(Request $request)
    {
        try {
            $hospital = Auth::user()->hospital;

            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Delete old logo if exists and not a seeded default image
            if ($hospital->logo && file_exists(public_path($hospital->logo)) && !str_contains($hospital->logo, 'images/hospital_logo/')) {
                unlink(public_path($hospital->logo));
            }

            // Upload new logo
            $file = $request->file('logo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = 'uploads/hospitals/' . $filename;

            // Create directory if not exists
            if (!file_exists(public_path('uploads/hospitals'))) {
                mkdir(public_path('uploads/hospitals'), 0777, true);
            }

            $file->move(public_path('uploads/hospitals'), $filename);

            $hospital->logo = $path;
            $hospital->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo updated successfully!',
                'logo_url' => asset($path)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload a valid image (JPG, PNG, GIF) max 2MB.'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Upload logo error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stats via AJAX.
     */
    public function getStats()
    {
        try {
            $hospital = Auth::user()->hospital;

            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            $stats = [
                'total_doctors' => User::role('doctor')
                    ->where('hospital_id', $hospital->id)
                    ->where('is_active', true)
                    ->count(),
                'total_appointments' => Appointment::where('hospital_id', $hospital->id)->count(),
                'total_patients' => Appointment::where('hospital_id', $hospital->id)
                    ->distinct('patient_id')
                    ->count('patient_id'),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            \Log::error('Get stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get hospital info via AJAX.
     */
    public function getInfo()
    {
        try {
            $hospital = Auth::user()->hospital;

            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'hospital' => [
                    'address' => $hospital->address,
                    'phone' => $hospital->phone,
                    'email' => $hospital->email,
                    'active_since' => $hospital->created_at ? $hospital->created_at->format('F Y') : 'N/A',
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get hospital info error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load hospital information'
            ], 500);
        }
    }

    /**
     * Get recent doctors via AJAX.
     */
    public function getRecentDoctors()
    {
        try {
            $hospital = Auth::user()->hospital;

            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            $doctors = User::role('doctor')
                ->where('hospital_id', $hospital->id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->display_name,
                        'specialty' => $doctor->specialization,
                        'formatted_fee' => $doctor->formatted_fee,
                        'avatar_html' => $doctor->avatar_html,
                    ];
                });

            return response()->json([
                'success' => true,
                'doctors' => $doctors
            ]);
        } catch (\Exception $e) {
            \Log::error('Get recent doctors error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load recent doctors'
            ], 500);
        }
    }
}
