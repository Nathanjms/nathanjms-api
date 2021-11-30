<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'secret' => 'required|in:' . env('REGISTER_USER_SECRET')
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->CreateToken('appToken')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => [
                'value' => $token,
                'expiration' => now()->addMinutes(60 * 24 * env('TOKEN_EXPIRATION_DAYS'))->unix()
            ]
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid Username or Password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->CreateToken('appToken')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => [
                'value' => $token,
                'expiration' => now()->addMinutes(60 * 24 * env('TOKEN_EXPIRATION_DAYS'))->unix()
            ]
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        foreach ($request->user()->tokens as $token) {
            $token->delete();
        }

        return response([
            'message' => 'Logged Out'
        ], Response::HTTP_OK);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->firstOrFail();
        
        // Check new password isn't the same as old.
        if (Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Cannot reset to same password'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($user->tokens) {
            // Delete all previous tokens
            foreach ($user->tokens as $token) {
                $token->delete();
            }
        }

        // Update password & save
        $user->password = bcrypt($fields['password']);
        $user->save();

        return response(['success' => true], Response::HTTP_ACCEPTED);
    }
}
