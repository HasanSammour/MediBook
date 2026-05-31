<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserCredentialsMail;
use Illuminate\Validation\Rule;



class UserController extends Controller
{
    /**
     * Display users list page.
     */
    public function index()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('admin.users.index', compact('hospitals'));
    }

    /**
     * Get all specialties list
     */
    private function getSpecialtiesList()
    {
        return [
            'Cardiology',
            'Neurology',
            'Pediatrics',
            'Orthopedics',
            'Dermatology',
            'Psychiatry',
            'Ophthalmology',
            'Dentistry',
            'Gynecology',
            'Urology',
            'Radiology',
            'Oncology',
            'Emergency Medicine',
            'Internal Medicine',
            'Endocrinology',
            'Gastroenterology',
            'Nephrology',
            'Pulmonology',
            'Rheumatology',
            'Hematology'
        ];
    }

    /**
     * Get users data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->role($request->role);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by hospital
        if ($request->filled('hospital_id') && $request->hospital_id !== 'all') {
            $query->where('hospital_id', $request->hospital_id);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Include soft deleted for trash view
        if ($request->filled('trash') && $request->trash === 'true') {
            $query->onlyTrashed();
        }

        // Apply sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $users = $query->paginate(10);

        // Transform data for response
        $users->getCollection()->transform(function ($user) {
            // Get hospital name
            $hospitalName = '-';
            if ($user->hospital_id) {
                $hospital = Hospital::find($user->hospital_id);
                $hospitalName = $hospital ? $hospital->name : '-';
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'display_name' => $user->display_name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'role' => $user->getRoleNames()->first() ?? 'No Role',
                'role_display' => $user->role_display_name,
                'hospital_name' => $hospitalName,
                'hospital_id' => $user->hospital_id,
                'is_active' => $user->is_active,
                'avatar_html' => $user->avatar_html,
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
                'deleted_at' => $user->deleted_at,
                'specialization' => $user->specialization,
                'consultation_fee' => $user->consultation_fee,
                'gender' => $user->gender,
                'date_of_birth' => $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'total' => $users->total(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
        ]);
    }

    /**
     * Show create user page with tabs.
     */
    public function create()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        $specialties = $this->getSpecialtiesList();
        return view('admin.users.create', compact('hospitals', 'specialties'));
    }

    /**
     * Store a new user based on role.
     */
    public function store(Request $request)
    {
        try {
            $role = $request->role;
            $password = Str::random(10);

            // Common validation for all roles
            $rules = [
                'role' => 'required|in:system_admin,hospital_admin,doctor,patient',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
            ];

            // Role-specific validation
            if ($role === 'hospital_admin' || $role === 'doctor') {
                $rules['hospital_id'] = 'required|exists:hospitals,id';
            }

            if ($role === 'doctor') {
                $rules['specialization'] = 'required|string|max:255';
                $rules['consultation_fee'] = 'required|numeric|min:0|max:1000';
            }

            $request->validate($rules);

            // Prepare user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make($password),
                'is_active' => true,
            ];

            // Role-specific fields
            if ($role === 'hospital_admin' || $role === 'doctor') {
                $userData['hospital_id'] = $request->hospital_id;
            }

            if ($role === 'doctor') {
                $userData['specialization'] = $request->specialization;
                $userData['consultation_fee'] = $request->consultation_fee;
                $userData['is_available'] = true;

                // Set default working hours for new doctor
                $workingHours = [];
                $availability = [];
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($days as $day) {
                    $isWorking = !in_array($day, ['saturday', 'sunday']);
                    if ($isWorking) {
                        $workingHours[$day] = ['enabled' => true, 'start' => '09:00', 'end' => '17:00'];
                        $availability[$day] = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
                    } else {
                        $workingHours[$day] = ['enabled' => false, 'start' => null, 'end' => null];
                        $availability[$day] = [];
                    }
                }
                $userData['working_hours'] = json_encode($workingHours);
                $userData['availability'] = json_encode($availability);
            }

            $user = User::create($userData);
            $user->assignRole($role);

            // Send credentials email
            try {
                Mail::to($user->email)->send(new UserCredentialsMail($user, $password, $role));
            } catch (\Exception $e) {
                \Log::error('Failed to send user credentials email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully! Login credentials have been sent to ' . $user->email,
                'user_id' => $user->id,
                'redirect' => route('admin.users.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store user error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload avatar separately (AJAX).
     */
    public function uploadAvatar(Request $request, User $user)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old avatar if exists and not a seeded default
            if ($user->profile_image && file_exists(public_path($user->profile_image)) && !str_contains($user->profile_image, 'images/')) {
                unlink(public_path($user->profile_image));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            // Determine folder based on role
            $role = $user->getRoleNames()->first();
            $folder = 'uploads/users/' . ($role ?? 'general');
            $avatarPath = $folder . '/' . $filename;

            // Create directory if not exists
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0777, true);
            }

            $file->move(public_path($folder), $filename);

            $user->profile_image = $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully!',
                'avatar_url' => asset($avatarPath),
                'avatar_html' => $user->avatar_html
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload a valid image (JPG, PNG, GIF) max 2MB.'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Upload avatar error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        $hospitals = Hospital::where('is_active', true)->get();
        $specialties = $this->getSpecialtiesList();
        $role = $user->getRoleNames()->first();
        return view('admin.users.edit', compact('user', 'hospitals', 'role', 'specialties'));
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        try {
            $role = $user->getRoleNames()->first();

            $rules = [
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'date_of_birth' => 'nullable|date|before:today',
                'is_active' => 'required|boolean',
            ];

            if ($role === 'hospital_admin' || $role === 'doctor') {
                $rules['hospital_id'] = 'required|exists:hospitals,id';
            }

            if ($role === 'doctor') {
                $rules['specialization'] = 'required|string|max:255';
                $rules['consultation_fee'] = 'required|numeric|min:0|max:1000';
            }

            $request->validate($rules);

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($role === 'hospital_admin' || $role === 'doctor') {
                $userData['hospital_id'] = $request->hospital_id;
            }

            if ($role === 'doctor') {
                $userData['specialization'] = $request->specialization;
                $userData['consultation_fee'] = $request->consultation_fee;
            }

            $user->update($userData);

            // Handle avatar removal
            if ($request->has('remove_avatar') && $request->remove_avatar == '1') {
                if ($user->profile_image && file_exists(public_path($user->profile_image)) && !str_contains($user->profile_image, 'images/')) {
                    unlink(public_path($user->profile_image));
                }
                $user->profile_image = null;
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'redirect' => route('admin.users.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update user error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $newStatus = !$user->is_active;
            $user->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'User activated successfully!' : 'User deactivated successfully!',
                'is_active' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(User $user)
    {
        try {
            $newPassword = Str::random(10);
            $user->update(['password' => Hash::make($newPassword)]);

            $role = $user->getRoleNames()->first();

            try {
                Mail::to($user->email)->send(new UserCredentialsMail($user, $newPassword, $role));
            } catch (\Exception $e) {
                \Log::error('Failed to send password reset email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully! New credentials have been sent to ' . $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "{$userName} has been moved to trash."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trash()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('admin.users.trash', compact('hospitals'));
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $userName = $user->name;
            $user->restore();

            return response()->json([
                'success' => true,
                'message' => "{$userName} has been restored successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $userName = $user->name;

            // Delete avatar if exists and not a seeded default
            if ($user->profile_image && file_exists(public_path($user->profile_image)) && !str_contains($user->profile_image, 'images/')) {
                unlink(public_path($user->profile_image));
            }

            $user->forceDelete();

            return response()->json([
                'success' => true,
                'message' => "{$userName} has been permanently deleted."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete user: ' . $e->getMessage()
            ], 500);
        }
    }
}
