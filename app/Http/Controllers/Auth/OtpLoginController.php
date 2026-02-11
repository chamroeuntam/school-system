<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpLoginController extends Controller
{
    public function show()
    {
        return view('auth.login-otp');
    }

    public function send(Request $request, OtpService $otpService)
    {
        try {
            $data = $request->validate([
                'phone' => ['required','string','max:20'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
            throw $e;
        }

        $user = User::where('phone', $data['phone'])->first();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Phone not found.'], 404);
            }
            return back()->with('error', 'Phone not found.');
        }

        // basic rate limit: allow 1 per 30 seconds per session
        // Disabled for testing purposes — uncomment to re-enable rate limiting.
        /*
        if (session('otp_last_sent_at') && now()->diffInSeconds(session('otp_last_sent_at')) < 30) {
            $msg = 'Please wait a moment before requesting OTP again.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 429);
            }
            return back()->with('error', $msg);
        }
        */

        try {
            $otpService->sendOtpToTelegram($user);
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->with('error', $e->getMessage());
        }

        session(['otp_phone' => $user->phone, 'otp_last_sent_at' => now()]);
        $msg = 'OTP sent to Telegram. Please check your bot messages.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg], 200);
        }
        return back()->with('success', $msg);
    }

    public function verify(Request $request, OtpService $otpService)
    {
        try {
            $data = $request->validate([
                'otp' => ['required','digits:6'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
            throw $e;
        }

        $phone = session('otp_phone');
        if (!$phone) {
            $msg = 'Session expired. Send OTP again.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'User not found.'], 404);
            }
            return back()->with('error', 'User not found.');
        }

        if (!$otpService->verify($user, $data['otp'])) {
            $msg = 'Invalid OTP.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        Auth::login($user);
        session()->forget(['otp_phone','otp_last_sent_at']);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Login successful!'], 200);
        }

        return redirect('/dashboard');
    }
}
