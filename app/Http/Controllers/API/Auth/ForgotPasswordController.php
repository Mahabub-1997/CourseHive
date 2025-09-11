<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller

{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999); // 6-digit OTP
        $user = User::where('email', $request->email)->first();

        $user->otp = $otp;
        $user->otp_created_at = Carbon::now();
        $user->save();

        // Send Email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        // Return OTP in response (for testing/debugging)
        return response()->json([
            'message' => 'OTP sent to your email',
            'otp' => $otp // ⚠️ Remove in production for security
        ]);
    }
    public function verifyOtpRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
//            ->where('otp_expiry', '>=', now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

/// Check OTP expiry (optional)
        if (Carbon::parse($user->otp_created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        // Mark user as verified for registration/login purposes
        $user->is_verified = true;
        $user->save();

        return response()->json(['message' => 'OTP verified successfully.']);
    }
}
