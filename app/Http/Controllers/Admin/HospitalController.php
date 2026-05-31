<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HospitalController extends Controller
{
    /**
     * Display hospitals list page.
     */
    public function index()
    {
        return view('admin.hospitals.index');
    }

    /**
     * Get hospitals data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $query = Hospital::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('trash') && $request->trash === 'true') {
            $query->onlyTrashed();
        }

        $sortField = $request->get('sort_field', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $hospitals = $query->paginate(10);

        $hospitals->getCollection()->transform(function ($hospital) {
            return [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'email' => $hospital->email,
                'phone' => $hospital->phone,
                'address' => $hospital->address,
                'location' => $this->getCityFromAddress($hospital->address),
                'doctors_count' => User::role('doctor')->where('hospital_id', $hospital->id)->count(),
                'is_active' => $hospital->is_active,
                'logo_url' => $this->getLogoUrl($hospital),
                'created_at' => $hospital->created_at ? $hospital->created_at->format('Y-m-d') : 'N/A',
                'deleted_at' => $hospital->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $hospitals->items(),
            'total' => $hospitals->total(),
            'from' => $hospitals->firstItem(),
            'to' => $hospitals->lastItem(),
            'current_page' => $hospitals->currentPage(),
            'last_page' => $hospitals->lastPage(),
        ]);
    }

    private function getCityFromAddress($address)
    {
        if (empty($address)) return 'N/A';
        $parts = explode(',', $address);
        $city = trim(end($parts));
        if (strlen($city) > 30) {
            $city = substr($city, 0, 30) . '...';
        }
        return $city;
    }

    private function getLogoUrl($hospital)
    {
        if (empty($hospital->logo)) {
            return null;
        }

        // Check if file exists
        if (file_exists(public_path($hospital->logo))) {
            return asset($hospital->logo);
        }

        return null;
    }

    /**
     * Show create hospital form.
     */
    public function create()
    {
        return view('admin.hospitals.create');
    }

    /**
     * Store a new hospital.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:hospitals,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
            ]);

            $hospital = Hospital::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hospital added successfully!',
                'hospital_id' => $hospital->id,
                'redirect' => route('admin.hospitals.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store hospital error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add hospital: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload logo separately (AJAX after form submission).
     */
    public function uploadLogo(Request $request, Hospital $hospital)
    {
        try {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old logo if exists
            if ($hospital->logo && file_exists(public_path($hospital->logo))) {
                unlink(public_path($hospital->logo));
            }

            $file = $request->file('logo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $logoPath = 'uploads/hospitals/' . $filename;

            // Create directory if not exists
            if (!file_exists(public_path('uploads/hospitals'))) {
                mkdir(public_path('uploads/hospitals'), 0777, true);
            }

            $file->move(public_path('uploads/hospitals'), $filename);

            $hospital->logo = $logoPath;
            $hospital->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully!',
                'logo_url' => asset($logoPath)
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
     * Show edit hospital form.
     */
    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.edit', compact('hospital'));
    }

    /**
     * Update a hospital.
     */
    public function update(Request $request, Hospital $hospital)
    {
        try {
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
                'is_active' => $request->boolean('is_active'),
            ]);

            // Handle logo removal
            if ($request->has('remove_logo') && $request->remove_logo == '1') {
                if ($hospital->logo && file_exists(public_path($hospital->logo))) {
                    unlink(public_path($hospital->logo));
                }
                $hospital->logo = null;
                $hospital->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Hospital updated successfully!',
                'redirect' => route('admin.hospitals.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update hospital error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update hospital: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Hospital $hospital)
    {
        try {
            $newStatus = !$hospital->is_active;
            $hospital->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'Hospital activated successfully!' : 'Hospital deactivated successfully!',
                'is_active' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Hospital $hospital)
    {
        try {
            $hospitalName = $hospital->name;
            $hospital->delete();

            return response()->json([
                'success' => true,
                'message' => "{$hospitalName} has been moved to trash."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hospital: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trash()
    {
        return view('admin.hospitals.trash');
    }

    public function restore($id)
    {
        try {
            $hospital = Hospital::withTrashed()->findOrFail($id);
            $hospitalName = $hospital->name;
            $hospital->restore();

            return response()->json([
                'success' => true,
                'message' => "{$hospitalName} has been restored successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore hospital: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $hospital = Hospital::withTrashed()->findOrFail($id);
            $hospitalName = $hospital->name;

            if ($hospital->logo && file_exists(public_path($hospital->logo))) {
                unlink(public_path($hospital->logo));
            }

            $hospital->forceDelete();

            return response()->json([
                'success' => true,
                'message' => "{$hospitalName} has been permanently deleted."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete hospital: ' . $e->getMessage()
            ], 500);
        }
    }
}
