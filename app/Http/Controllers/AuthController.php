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
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $token = $user->createToken('token-name')->plainTextToken;
        $user->update(['token' => $token]);
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // User Login
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.']
    //         ]);
    //     }

    //     return response()->json([
    //         'user' => $user,
    //         'token' => $user->createToken('token-name')->plainTextToken
    //     ]);
    // }

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
}
