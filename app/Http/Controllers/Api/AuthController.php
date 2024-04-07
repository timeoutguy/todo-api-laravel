<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
        return ApiResponseClass::sendResponses($response, 'User registered successfully', 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $credentials = $request->only(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return ApiResponseClass::sendError('Unauthorized', 'Invalid credentials', 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];

        return ApiResponseClass::sendResponses($response, 'User authenticated', 200);
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return ApiResponseClass::sendResponses([], 'Logout successfull', 200);
    }
}
