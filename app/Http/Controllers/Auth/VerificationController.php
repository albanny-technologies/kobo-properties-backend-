<?php 

// In app/Http/Controllers/Auth/VerificationController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EmailVerification;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'verification_code' => 'required|string|size:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $user = User::where('email', $request->email)
                    ->where('verification_code', $request->verification_code)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null; // Remove the verification code
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    }
    public function resendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required',
        ]);
        $user = User::where('email', $validatedData['email'])->first();
        
        $verificationCode = Str::random(6);

        $user->verification_code = $verificationCode;
        $user->save();

        $user->notify(new EmailVerification($user));
        return response()->json([
            'status' => true,
            'message' => 'Verification code has been resend',
        ]);
    }
}
