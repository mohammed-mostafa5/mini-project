<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register()
    {
        $validated = request()->validate([
            'name'      => 'required|string|min:3|max:191',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:3|max:191|confirmed'
        ]);

        $user = User::create($validated);

        return response()->json([
            'message' => 'User registered Successfully',
            'user'    => $user
        ]);
    }

    public function login()
    {
        $credentials = request()->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|max:191'
        ]);

        if (!Auth::attempt($credentials) ) {
            throw ValidationException::withMessages(['password' => 'Wrong password!, Please try again']);
        }

        $token = request()->user()->createToken('token');

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You logged out Successfully']);
    }
}
