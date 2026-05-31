<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        try {
            $admin = Auth::user();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
            ]);

            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $admin = Auth::user();

            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|min:6|confirmed',
            ]);

            $admin->update([
                'password' => Hash::make($request->password)
            ]);

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
     * Upload admin avatar.
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $admin = Auth::user();

            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Delete old avatar if exists and not a seeded default
            if ($admin->profile_image && file_exists(public_path($admin->profile_image)) && !str_contains($admin->profile_image, 'images/')) {
                unlink(public_path($admin->profile_image));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $folder = 'uploads/admins';
            $avatarPath = $folder . '/' . $filename;

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0777, true);
            }

            $file->move(public_path($folder), $filename);

            $admin->profile_image = $avatarPath;
            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully!',
                'avatar_url' => asset($avatarPath),
                'avatar_html' => $admin->avatar_html
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload a valid image (JPG, PNG, GIF) max 2MB.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: ' . $e->getMessage()
            ], 500);
        }
    }
}