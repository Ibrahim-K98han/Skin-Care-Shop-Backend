<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private OtpService $otp)
    {
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
        ], [
            'phone.regex' => 'সঠিক মোবাইল নম্বর দিন (যেমন 01712345678)।',
        ]);

        $this->otp->send($data['phone']);

        return response()->json([
            'success' => true,
            'message' => 'OTP পাঠানো হয়েছে।',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'code' => ['required', 'digits:6'],
            'name' => ['nullable', 'string', 'max:100'],
        ]);

        if (! $this->otp->verify($data['phone'], $data['code'])) {
            throw ValidationException::withMessages([
                'code' => ['OTP ভুল অথবা মেয়াদ শেষ।'],
            ]);
        }

        // নম্বর থাকলে লগইন, না থাকলে নতুন অ্যাকাউন্ট
        $user = User::firstOrCreate(
            ['phone' => $data['phone']],
            ['name' => $data['name'] ?? 'Customer']
        );

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true]);
    }
}
