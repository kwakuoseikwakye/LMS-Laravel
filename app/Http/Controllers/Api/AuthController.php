<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validated->fails()) {
            return apiErrorResponse("Signup failed. " . join(". ", $validated->errors()->all()), 422);
        }

        $data = $validated->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return apiSuccessResponse('Signup successful', 200, $userData);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validated->fails()) {
            return apiErrorResponse("Login failed. " . join(". ", $validated->errors()->all()), 422);
        }

        $data = $validated->validated();
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return apiErrorResponse('The provided credentials are incorrect.', 401);
        }

        $userData = [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
        return apiSuccessResponse('Login successful', 200, $userData);
    }
}
