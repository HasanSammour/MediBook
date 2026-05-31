<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalSearchController extends Controller
{
    /**
     * Display find hospitals page.
     */
    public function index()
    {
        return view('patient.search.hospitals');
    }
    
    /**
     * Get hospitals data via AJAX with filters.
     */
    public function getData(Request $request)
    {
        $query = Hospital::where('is_active', true)
            ->withCount('doctors');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->location;
            $query->where('address', 'like', "%{$location}%");
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        if ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'doctors') {
            $query->orderBy('doctors_count', 'desc');
        }

        $hospitals = $query->paginate(6);

        // Transform the response to include logo_url
        $hospitalsData = $hospitals->items();
        $formattedHospitals = array_map(function($hospital) {
            return [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'address' => $hospital->address,
                'phone' => $hospital->phone,
                'email' => $hospital->email,
                'doctors_count' => $hospital->doctors_count,
                'logo_url' => $hospital->logo_url,
            ];
        }, $hospitalsData);

        return response()->json([
            'success' => true,
            'data' => $formattedHospitals,
            'total' => $hospitals->total(),
            'from' => $hospitals->firstItem(),
            'to' => $hospitals->lastItem(),
            'current_page' => $hospitals->currentPage(),
            'last_page' => $hospitals->lastPage(),
        ]);
    }

    /**
     * Get hospital details for SweetAlert modal using Route Model Binding
     */
    public function showHospital(Hospital $hospital)
    {
        if (!$hospital->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found'
            ], 404);
        }

        $doctorsCount = $hospital->doctors()->count();

        return response()->json([
            'success' => true,
            'hospital' => [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'address' => $hospital->address,
                'phone' => $hospital->phone,
                'email' => $hospital->email,
                'doctors_count' => $doctorsCount,
                'logo_url' => $hospital->logo_url,
            ]
        ]);
    }
}