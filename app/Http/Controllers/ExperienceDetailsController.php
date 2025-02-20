<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use App\Models\exp_details;
use App\Models\User;

class ExperienceDetailsController extends Controller
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
               
                'last_company' => 'nullable|string|max:255',
                'exp_start_date' => 'nullable|date',
                'exp_end_date' => 'nullable|date',
                'last_designation' => 'nullable|string|max:255',
                'last_salary' => 'nullable|numeric',
                'current_exp' => 'nullable|numeric',
                'current_salary' => 'nullable|numeric',
                'total_exp' => 'nullable|numeric',
                'payslip1' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'payslip2' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'payslip3' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'offer_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'exp_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'inc_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5000',
                'UAN' => 'nullable|string|max:20',
        ]);

        // Handle file uploads (store in storage/app/exp_documents)
        $payslip1Path = $request->hasFile('payslip1') ? $request->file('payslip1')->store('exp_documents') : null;
        $payslip2Path = $request->hasFile('payslip2') ? $request->file('payslip2')->store('exp_documents') : null;
        $payslip3Path = $request->hasFile('payslip3') ? $request->file('payslip3')->store('exp_documents') : null;
        $offerLetterPath = $request->hasFile('offer_letter') ? $request->file('offer_letter')->store('exp_documents') : null;
        $expLetterPath = $request->hasFile('exp_letter') ? $request->file('exp_letter')->store('exp_documents') : null;
        $incLetterPath = $request->hasFile('inc_letter') ? $request->file('inc_letter')->store('exp_documents') : null;

        // Create and save experience details
        $expDetails = exp_details::create([
                'user_id' => $request->user_id,
                'last_company' => $request->last_company,
                'exp_start_date' => $request->exp_start_date,
                'exp_end_date' => $request->exp_end_date,
                'last_designation' => $request->last_designation,
                'last_salary' => $request->last_salary,
                'current_exp' => $request->current_exp,
                'current_salary' => $request->current_salary,
                'total_exp' => $request->total_exp,
                'payslip1' => $payslip1Path,
                'payslip2' => $payslip2Path,
                'payslip3' => $payslip3Path,
                'offer_letter' => $offerLetterPath,
                'exp_letter' => $expLetterPath,
                'inc_letter' => $incLetterPath,
                'UAN' => $request->UAN,
        ]);

        return response()->json([
                'message' => 'Experience details saved successfully',
                'data' => $expDetails
        ], 201);
    }


    public function update(Request $request, $id)
    {
        // Find the existing record
        $expDetails = exp_details::find($id);

        if (!$expDetails) {
                return response()->json(['message' => 'Record not found'], 404);
        }

        // Validate the request data
        $request->validate([
                'user_id' => 'nullable|integer',
                'last_company' => 'nullable|string|max:255',
                'exp_start_date' => 'nullable|date',
                'exp_end_date' => 'nullable|date',
                'last_designation' => 'nullable|string|max:255',
                'last_salary' => 'nullable|numeric',
                'current_exp' => 'nullable|numeric',
                'current_salary' => 'nullable|numeric',
                'total_exp' => 'nullable|numeric',
                'payslip1' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'payslip2' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'payslip3' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'offer_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'exp_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'inc_letter' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'UAN' => 'nullable|string|max:20',
        ]);

        // Handle file updates (delete old files and store new ones)
        $storagePath = 'exp_documents';

        if ($request->hasFile('payslip1')) {
                Storage::delete($expDetails->payslip1); // Delete old file
                $expDetails->payslip1 = $request->file('payslip1')->store($storagePath);
        }
        if ($request->hasFile('payslip2')) {
                Storage::delete($expDetails->payslip2);
                $expDetails->payslip2 = $request->file('payslip2')->store($storagePath);
        }
        if ($request->hasFile('payslip3')) {
                Storage::delete($expDetails->payslip3);
                $expDetails->payslip3 = $request->file('payslip3')->store($storagePath);
        }
        if ($request->hasFile('offer_letter')) {
                Storage::delete($expDetails->offer_letter);
                $expDetails->offer_letter = $request->file('offer_letter')->store($storagePath);
        }
        if ($request->hasFile('exp_letter')) {
                Storage::delete($expDetails->exp_letter);
                $expDetails->exp_letter = $request->file('exp_letter')->store($storagePath);
        }
        if ($request->hasFile('inc_letter')) {
                Storage::delete($expDetails->inc_letter);
                $expDetails->inc_letter = $request->file('inc_letter')->store($storagePath);
        }

        // Update the rest of the fields
        $expDetails->update([
                'user_id' => $request->user_id ?? $expDetails->user_id,
                'last_company' => $request->last_company ?? $expDetails->last_company,
                'exp_start_date' => $request->exp_start_date ?? $expDetails->exp_start_date,
                'exp_end_date' => $request->exp_end_date ?? $expDetails->exp_end_date,
                'last_designation' => $request->last_designation ?? $expDetails->last_designation,
                'last_salary' => $request->last_salary ?? $expDetails->last_salary,
                'current_exp' => $request->current_exp ?? $expDetails->current_exp,
                'current_salary' => $request->current_salary ?? $expDetails->current_salary,
                'total_exp' => $request->total_exp ?? $expDetails->total_exp,
                'UAN' => $request->UAN ?? $expDetails->UAN,
        ]);

        return response()->json([
                'message' => 'Experience details updated successfully',
                'data' => $expDetails
        ], 200);
    }

    public function show($id)
    {
        // Find experience details by employee ID
        $expDetails = exp_details::where('user_id', $id)->first();

        // If no experience details found, return a not found response
        if (!$expDetails) {
                return response()->json([
                        'message' => 'Experience details not found'
                ], 404);
        }

        // Return the experience details
        return response()->json([
                'message' => 'Experience details fetched successfully',
                'data' => $expDetails
        ], 200);
    }

    public function index(Request $request)
    {
    $search = $request->input('search'); // Get the search query from the request

    // Fetch experience details with search and pagination (10 rows per page)
    $expDetails = exp_details::when($search, function ($query, $search) {
        return $query->where('last_company', 'like', "%{$search}%")
                     ->orWhere('UAN', 'like', "%{$search}%");
    })->paginate(10); // Pagination: 10 rows per page

    // Return the experience details with pagination
    return response()->json([
        'message' => 'Experience details fetched successfully',
        'data' => $expDetails
    ], 200);
    }


}
