<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangedMail;
use Illuminate\Validation\Rule;

class DoctorProfileController extends Controller
{
    /**
     * Show profile settings page.
     */
    public function edit()
    {
        $doctor = Auth::user();

        // Get all distinct specialties from doctors in database
        $specialties = User::role('doctor')
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization')
            ->toArray();

        // Sort alphabetically
        sort($specialties);

        // Get hospital name
        $hospitalName = $doctor->hospital ? $doctor->hospital->name : 'Independent Practice';

        return view('doctor.profile-settings', compact('doctor', 'specialties', 'hospitalName'));
    }

    /**
     * Update personal information.
     */
    public function updatePersonal(Request $request)
    {
        try {
            $doctor = Auth::user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($doctor->id)],
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
            ]);

            $doctor->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Personal information updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update professional information.
     */
    public function updateProfessional(Request $request)
    {
        try {
            $doctor = Auth::user();

            $validated = $request->validate([
                'specialization' => 'nullable|string|max:255',
                'consultation_fee' => 'nullable|numeric|min:0|max:1000',
            ]);

            $doctor->update([
                'specialization' => $validated['specialization'] ?? null,
                'consultation_fee' => $validated['consultation_fee'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Professional information updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $doctor = Auth::user();

            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|min:6|confirmed',
            ]);

            $doctor->update([
                'password' => Hash::make($request->password)
            ]);

            // Send password changed email
            try {
                Mail::to($doctor->email)->send(new PasswordChangedMail($doctor));
            } catch (\Exception $e) {
                \Log::error('Failed to send password change email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $doctor = Auth::user();

            // Delete old photo if exists and is not a seeded image
            if ($doctor->profile_image && !str_contains($doctor->profile_image, 'images/doctors/')) {
                $oldPath = public_path($doctor->profile_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store new photo
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'uploads/doctors/' . $filename;
            $file->move(public_path('uploads/doctors'), $filename);

            $doctor->profile_image = $path;
            $doctor->save();

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully!',
                'photo_url' => asset($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete account (soft delete).
     */
    public function destroy(Request $request)
    {
        try {
            $doctor = Auth::user();

            $request->validate([
                'password' => 'required|current_password',
            ]);

            $doctor->delete();

            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Your account has been deactivated.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate account: ' . $e->getMessage()
            ], 500);
        }
    }
}