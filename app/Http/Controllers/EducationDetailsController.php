<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\edu_details;
use App\Models\User;
use App\Http\Requests\EducationDetailRequest;  // Corrected the import statement
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EducationDetailsController extends Controller
{
    public function store(EducationDetailRequest $request)
    {
        try {
            // Get token from request headers
            $token = request()->header('employee');
            Log::info($token);
            if (!$token) {
                return response()->json(['message' => 'Unauthorized: No token provided'], 401);
            }
            // Remove 'Bearer ' prefix if present
            $token = str_replace('Bearer ', '', $token);
    
            // Find user by token
            $user = User::where('token', $token)->first();
            Log::info($user->id);
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized: Invalid token'
                ], 401);
            }
    
            // Creating a new edu_details instance and passing validated data except file fields
            $educationDetail = new edu_details($request->except(['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg']));
    
            // Assign the found user's ID to user_id in edu_details
            $educationDetail->user_id = $user->id;
    
            // File handling loop
            $fileFields = ['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg'];
            foreach ($fileFields as $fileField) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    $filePath = $file->store('education', 'public'); // Store in 'storage/app/public/education'
                    $educationDetail->$fileField = $filePath;
                }
            }
    
            // Save the new education details record
            $educationDetail->save();
            Log::info($request->all());

            // Return a success response with stored education details
            return response()->json([
                'message' => 'Education details stored successfully!',  
                'data' => $educationDetail
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(EducationDetailRequest $request, $id)
    {
        
        $educationDetail = edu_details::where("user_id", $id)->first();
        $educationDetail->update($request->except(['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg']));

        // File handling loop (same as before)
        $fileFields = ['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg'];
        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                $educationDetail->$fileField = $request->file($fileField)->store('education');
            }
        }

        $educationDetail->save();

        return response()->json(['message' => 'Education details updated successfully!', 'data' => $educationDetail], 200);
    }

    public function show($id)
    {
        // Retrieve the education details by ID
       
        try {$educationDetail = edu_details::where("user_id", $id)->first();

        // Return the education details in the response
            return response()->json([
                'data' => $educationDetail,
                'status' => true,
                'error_message' => null
            ], 200);
        } catch (\Exception $e) {
            // Return error response if user not found
            return response()->json([
                'data' => null,
                'status' => false,
                'error_message' => 'employee not found'
            ], 404);
        }
    }


    public function index(Request $request)
    {
        // Retrieve the search query parameter from the request
        $search = (string) $request->query('search', '');

        // Retrieve education details with pagination (10 rows per page)
        $educationDetails = edu_details::when($search, function ($query, $search) {
            return $query->where('ssc_schoole', 'like', "%{$search}%")
                        ->orWhere('PG_college', 'like', "%{$search}%");
        })->paginate(10); // Fetch only 10 rows per page

        // Return paginated data
        return response()->json($educationDetails, 200);
    }

    

}
