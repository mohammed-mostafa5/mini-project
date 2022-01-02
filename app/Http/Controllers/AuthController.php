<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request()->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|max:191'
        ]);

        if (!auth()->attempt($credentials)) {
            ValidationException::withMessages(['password' => 'Wrong password!, Please try again']);
        }

        $token = request()->user()->createToken('token');

        return response()->json(['token' => $token]);
    }
}
