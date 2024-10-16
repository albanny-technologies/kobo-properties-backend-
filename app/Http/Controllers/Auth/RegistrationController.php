<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_no' => 'nullable|string|max:20',
            'type' => 'required',
            'alt_phone_no' => 'nullable|string|max:20',
        ]);
        $verificationCode = Str::random(6); // Generate a random 6-digit code

        $user = User::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'type' => $validatedData['type'],
            'password' => Hash::make($validatedData['password']),
            'phone_no' => $validatedData['phone_no'] ?? null,
            'alt_phone_no' => $validatedData['alt_phone_no'] ?? null,
            'verification_code' => $verificationCode,
        ]);
        // Send verification email with code
        $user->notify(new EmailVerification($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
