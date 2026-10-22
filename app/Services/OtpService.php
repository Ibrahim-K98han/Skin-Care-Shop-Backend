<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function send(string $phone): void
    {
        // ৬০ সেকেন্ডের মধ্যে আবার পাঠানো যাবে না
        $tooSoon = OtpCode::where('phone', $phone)
            ->where('created_at', '>', now()->subSeconds(60))
            ->exists();

        if ($tooSoon) {
            throw ValidationException::withMessages([
                'phone' => ['৬০ সেকেন্ড পর আবার চেষ্টা করুন।'],
            ]);
        }

        // পুরনো OTP মুছে নতুন তৈরি
        OtpCode::where('phone', $phone)->delete();

        $code = (string) random_int(100000, 999999);

        OtpCode::create([
            'phone' => $phone,
            'code' => Hash::make($code), // হ্যাশ করে রাখছি, সরাসরি নয়
            'expires_at' => now()->addMinutes(5),
        ]);

        // TODO: এখানে পরে SMS গেটওয়ে বসবে
        Log::info("OTP for {$phone}: {$code}");
    }

    public function verify(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone', $phone)->latest()->first();

        if (! $otp || $otp->expires_at->isPast() || $otp->attempts >= 5) {
            return false;
        }

        $otp->increment('attempts');

        if (! Hash::check($code, $otp->code)) {
            return false;
        }

        $otp->delete();

        return true;
    }
}
