<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\edu_details;
use App\Models\exp_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\emp_details;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class EmployeesDetailsController extends Controller
{
    public function store(Request $request)
    {
        $token = request()->header('employee');
        if (!$token) {
                return response()->json(['message' => 'Unauthorized: No token provided'], 401);
            }

        $token = str_replace('Bearer ', '', $token);
        $user = User::where('token', $token)->first();
        if (!$user) {
                return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
            }
        
        // Validate the request data
        $request->validate([
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
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5000', // Validating image upload
        ]);

        // Handle file upload (store in storage/app/emp_photo)
        $photoPath = null;
        if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('emp_photo');
        }
        Log::info($user->id);
        // Create and save employee details
        $empDetails = emp_details::create([
                'user_id' => $user->id,
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

        $eduDetails = edu_details::create([
            'user_id' => $user->id,
        ]);
        $expDetails = exp_details::create([
            'user_id' => $user->id,
        ]);

        

        return response()->json([
                'message' => 'Employee details saved successfully',
                'data' => [$empDetails, $eduDetails, $expDetails]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Find the existing record
        
        $empDetails = emp_details::where("user_id", $id)->first();
        if (!$empDetails) {
                return response()->json(['message' => 'Record not found'], 404);
        }

        // Validate the request data
        $request->validate([
               
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
                // 'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:5000',
        ]);

        // Handle photo update (delete old photo if new one is uploaded)
        if ($request->hasFile('photo')) {
                Storage::delete($empDetails->photo); // Delete old photo
                $empDetails->photo = $request->file('photo')->store('emp_photo');
        }

        // Update other fields
        $empDetails->update([
                
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
        try {
            // Find the user by ID
        //     $emp_details = emp_details::findOrFail($id);
        $emp_details = emp_details::where("user_id", $id)->first();

            $user = User::where("id",$emp_details->reporting_manager_id)->first();

        
            return response()->json([
                'data' => $emp_details,
                'reporting_manager_id' => $user->first_name,
                'status' => true,
                'error_message' => null
            ], 200);
        } catch (\Exception $e) {
            // Return error response if user not found
            return response()->json([
                'data' => null,
                'status' => false,
                'error_message' => 'Emp not found'
            ], 404);
        }
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
