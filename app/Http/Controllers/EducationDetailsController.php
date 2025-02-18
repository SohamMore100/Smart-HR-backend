<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\edu_details;
use App\Http\Requests\EducationDetailRequest;  // Corrected the import statement
use Illuminate\Support\Facades\Storage;

class EducationDetailsController extends Controller
{
    public function store(EducationDetailRequest $request)
    {
        // Creating a new edu_details instance and passing validated data except file fields
        $educationDetail = new edu_details($request->except(['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg']));

        // File handling loop
        $fileFields = ['doc_ssc', 'doc_hsc', 'doc_graduation', 'doc_pg'];
        foreach ($fileFields as $fileField) {
            if ($request->hasFile($fileField)) {
                $educationDetail->$fileField = $request->file($fileField)->store('education');
            }
        }

        // Save the new education details record
        $educationDetail->save();

        // Return a success response with stored education details
        return response()->json(['message' => 'Education details stored successfully!', 'data' => $educationDetail], 201);
    }

    public function update(EducationDetailRequest $request, $id)
    {
        $educationDetail = edu_details::findOrFail($id);
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
        $educationDetail = edu_details::findOrFail($id);

        // Return the education details in the response
        return response()->json(['data' => $educationDetail], 200);
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
