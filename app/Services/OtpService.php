<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function sendOtpToTelegram(User $user): void
    {
        if (!$user->telegram_chat_id) {
            throw new \RuntimeException("User not linked with Telegram. Please /bind phone in bot first.");
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
        ]);

        $token = config('services.telegram.bot_token');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $user->telegram_chat_id,
            'text' => "🔐 OTP របស់អ្នក: {$otp}\n⏳ ផុតកំណត់ក្នុង 5 នាទី",
        ]);
    }

    public function verify(User $user, string $otp): bool
    {
        $code = OtpCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$code) return false;
        if ($code->attempts >= 5) return false;

        $code->increment('attempts');

        if (!Hash::check($otp, $code->otp_hash)) return false;

        $code->update(['used_at' => now()]);
        return true;
    }
}
