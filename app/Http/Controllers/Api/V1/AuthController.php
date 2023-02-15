<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'login success',
                    'token' => $token,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'wrong email or password',
            ], 401);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'user' => $user,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'user data fetched successfully',
                'user' => $request->user(),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'internal server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
