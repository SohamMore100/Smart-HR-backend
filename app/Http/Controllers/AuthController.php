<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // User Registration
    // public function register(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'first_name' => 'required|string|max:255',
    //             'middle_name' => 'required|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'required|string|email|max:255|unique:users',
    //             'password' => 'required|string|min:6',
    //             'role' => 'required|integer|min:1',
    //             'mobile' => 'required|digits:10'
    //         ]);

    //         $user = User::create([
    //             'first_name' => $validatedData['first_name'],
    //             'middle_name' => $validatedData['middle_name'],
    //             'last_name' => $validatedData['last_name'],
    //             'email' => $validatedData['email'],
    //             'password' => Hash::make($validatedData['password']),
    //             'mobile' => $validatedData['mobile'],
    //             'role' => $validatedData['role'],
    //         ]);

    //         $token = $user->createToken('employee')->plainTextToken;
    //         $user->update(['token' => $token]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User registered successfully',
    //             'user' => $user,
    //             'token' => $token
    //         ], 201)->header('employee', 'Bearer ' . $token);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|integer|min:1',
                'mobile' => 'required|digits:10'
            ]);

            // Ensure employee_id is generated and assigned
           
         

            $lastEmployee = User::orderBy('id', 'desc')->first();

            if ($lastEmployee && isset($lastEmployee->employee_id)) {
                // Extract numeric part and increment
                $lastIdNumber = intval(substr($lastEmployee->employee_id, 1)); 
                $newEmployeeId = 'A' . ($lastIdNumber + 1);
            } else {
                // If table is empty, start from A1001
                $newEmployeeId = 'A1001';
            }
            $user = User::create([
                'employee_id' => $newEmployeeId, 
                'first_name' => $validatedData['first_name'],
                'middle_name' => $validatedData['middle_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'mobile' => $validatedData['mobile'],
                'role' => $validatedData['role'],
            ]);

            $token = $user->createToken('employee')->plainTextToken;
            $user->update(['token' => $token]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'employee_id' => $newEmployeeId,
                'user' => $user,
                'token' => $token
            ], 201)->header('employee', 'Bearer ' . $token);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Generate a new token
        $newToken = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'user' => $user
        ], 200)->withHeaders([
            'Authorization' => 'Bearer ' . $newToken, // Newly created token
            'Stored-Token' => 'Bearer ' . $user->token // Token stored in the database
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'mobile' => 'required|integer|min:10',
            'role' => 'required|integer|min:1', // Adjust the min role based on your needs
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user details
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => $request->role,
        ]);

        // Return response
        return response()->json([
            'message' => 'User details updated successfully',
            'user' => $user
        ]);
    }

 
    public function show($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Return the user data with status and no error message
            return response()->json([
                'data' => $user,
                'status' => true,
                'error_message' => null
            ], 200);
        } catch (\Exception $e) {
            // Return error response if user not found
            return response()->json([
                'data' => null,
                'status' => false,
                'error_message' => 'User not found'
            ], 404);
        }
    }



    public function index(Request $request)
    {
        try {
            // Get search query if provided
            $search = $request->input('search');
    
            // Query the User model
            $users = User::when($search, function ($query, $search) {
                    return $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('mobile', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                })
                ->paginate(10); // Paginate results, 10 per page
    
            // Role Mapping
            $roleMapping = [
                1 => 'HR',
                2 => 'Intern',
                3 => 'Junior Associate Engineer',
                4 => 'Associate Engineer',
                5 => 'Project Manager',
                6 => 'Data Entry Executive'
            ];
    
            // Modify role field
            $users->getCollection()->transform(function ($user) use ($roleMapping) {
                $user->role_name = $roleMapping[$user->role] ?? 'Unknown Role';
                return $user;
            });
    
            // Return success response
            return response()->json([
                'status' => true,
                'data' => $users,
                'error' => null
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    


}
