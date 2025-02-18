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
    public function register(Request $request)
    {
        $request->validate([
            // 'name' => 'required|string|max:255',
            'first_name'=>'required|string|max:255',
            'middle_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'=>'required|integer|min:6',
            'mobile'=>'required|integer|min:10'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'role'=>$request->role,
        ]);
        
        $token = $user->createToken('token-name')->plainTextToken;
        $user->update(['token' => $token]);
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
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
        // Find the user by ID
        $user = User::findOrFail($id);

        // Return the user data as a response
        return response()->json([
            'user' => $user
        ]);
    }

    public function index(Request $request)
    {
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

        // Return the users with pagination data
        return response()->json([
            'users' => $users
        ]);
    }


}
