<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\emp_details;

class emp_details_controller extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
                'user_id' => 'nullable|integer',
                'reporting_manager_id' => 'nullable|integer',
                'aadhar' => 'nullable|string|max:20',
                'pan' => 'nullable|string|max:10',
                'dob' => 'nullable|date',
                'gender' => 'nullable|integer',
                'alternate_mobile' => 'nullable|string|max:15',
                'address1' => 'nullable|string|max:255',
                'address2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'pin_code' => 'nullable|string|max:10',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validating image upload
        ]);

        // Handle file upload (store in storage/app/emp_photo)
        $photoPath = null;
        if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('emp_photo');
        }

        // Create and save employee details
        $empDetails = emp_details::create([
                'user_id' => $request->user_id,
                'reporting_manager_id' => $request->reporting_manager_id,
                'aadhar' => $request->aadhar,
                'pan' => $request->pan,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'alternate_mobile' => $request->alternate_mobile,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'pin_code' => $request->pin_code,
                'photo' => $photoPath, // Store the path in the database
        ]);

        return response()->json([
                'message' => 'Employee details saved successfully',
                'data' => $empDetails
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Find the existing record
        $empDetails = EmpDetails::find($id);

        if (!$empDetails) {
                return response()->json(['message' => 'Record not found'], 404);
        }

        // Validate the request data
        $request->validate([
                'user_id' => 'nullable|integer',
                'reporting_manager_id' => 'nullable|integer',
                'aadhar' => 'nullable|string|max:20',
                'pan' => 'nullable|string|max:10',
                'dob' => 'nullable|date',
                'gender' => 'nullable|integer',
                'alternate_mobile' => 'nullable|string|max:15',
                'address1' => 'nullable|string|max:255',
                'address2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'pin_code' => 'nullable|string|max:10',
                'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo update (delete old photo if new one is uploaded)
        if ($request->hasFile('photo')) {
                Storage::delete($empDetails->photo); // Delete old photo
                $empDetails->photo = $request->file('photo')->store('emp_photo');
        }

        // Update other fields
        $empDetails->update([
                'user_id' => $request->user_id ?? $empDetails->user_id,
                'reporting_manager_id' => $request->reporting_manager_id ?? $empDetails->reporting_manager_id,
                'aadhar' => $request->aadhar ?? $empDetails->aadhar,
                'pan' => $request->pan ?? $empDetails->pan,
                'dob' => $request->dob ?? $empDetails->dob,
                'gender' => $request->gender ?? $empDetails->gender,
                'alternate_mobile' => $request->alternate_mobile ?? $empDetails->alternate_mobile,
                'address1' => $request->address1 ?? $empDetails->address1,
                'address2' => $request->address2 ?? $empDetails->address2,
                'city' => $request->city ?? $empDetails->city,
                'state' => $request->state ?? $empDetails->state,
                'country' => $request->country ?? $empDetails->country,
                'pin_code' => $request->pin_code ?? $empDetails->pin_code,
        ]);

        return response()->json([
                'message' => 'Employee details updated successfully',
                'data' => $empDetails
        ], 200);
    }

    public function show($id)
    {
        // Find employee details by ID
        $empDetails = emp_details::find($id);

        // If no details found, return a not found response
        if (!$empDetails) {
                return response()->json([
                        'message' => 'Employee details not found'
                ], 404);
        }

        // Return the employee details
        return response()->json([
                'message' => 'Employee details fetched successfully',
                'data' => $empDetails
        ], 200);
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search query from the request

        // Fetch employee details with search and pagination (10 rows per page)
        $empDetails = emp_details::when($search, function ($query, $search) {
                return $query->where('aadhar', 'like', "%{$search}%")
                                ->orWhere('pan', 'like', "%{$search}%");
        })->paginate(10); // Pagination: 10 rows per page

        // Return the employee details with pagination
        return response()->json([
                'message' => 'Employee details fetched successfully',
                'data' => $empDetails
        ], 200);
    }


}
