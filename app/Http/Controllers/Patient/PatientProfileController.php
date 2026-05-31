<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeletedMail;
use App\Mail\EmailChangedMail;
use Carbon\Carbon;

class PatientProfileController extends Controller
{
    /**
     * Show profile settings page.
     */
    public function show()
    {
        $user = Auth::user();
        $age = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->age : null;
        return view('patient.profile-settings', compact('user', 'age'));
    }

    /**
     * Update profile information (includes gender and date of birth).
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
            ]);

            $oldEmail = $user->email;

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            // Send email notification if email changed
            if ($oldEmail !== $request->email) {
                try {
                    Mail::to($request->email)->send(new EmailChangedMail($user, $oldEmail));
                } catch (\Exception $e) {
                    \Log::error('Failed to send email change notification: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()[array_key_first($e->errors())][0]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|min:6|confirmed',
            ]);

            Auth::user()->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $message = '';
            if (isset($errors['current_password'])) {
                $message = 'Current password is incorrect.';
            } elseif (isset($errors['password'])) {
                $message = $errors['password'][0];
            } else {
                $message = 'Please check your input.';
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
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
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = Auth::user();

            // Delete old photo if exists and not a default seeded image
            if ($user->profile_image && !str_contains($user->profile_image, 'images/')) {
                $oldPath = public_path($user->profile_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store new photo
            $file = $request->file('photo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = 'uploads/patients/' . $filename;

            // Create directory if not exists
            if (!file_exists(public_path('uploads/patients'))) {
                mkdir(public_path('uploads/patients'), 0777, true);
            }

            $file->move(public_path('uploads/patients'), $filename);

            $user->profile_image = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully!',
                'photo_url' => asset($path)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload a valid image (JPG, PNG, GIF) max 2MB.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete account.
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|current_password',
            ]);

            $user = Auth::user();

            // Send goodbye email
            try {
                Mail::to($user->email)->send(new AccountDeletedMail($user));
            } catch (\Exception $e) {
                \Log::error('Failed to send account deletion email: ' . $e->getMessage());
            }

            // Soft delete the user
            $user->delete();

            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Your account has been deactivated.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}