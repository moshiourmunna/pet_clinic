<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function __construct(private OtpService $otp) {}

    private function phone(Request $request): string
    {
        $request->merge(['phone' => preg_replace('/[^\d+]/', '', (string) $request->phone)]);
        return $request->validate(['phone' => ['required', 'regex:/^(\+?880|0)1[3-9]\d{8}$/']])['phone'];
    }

    public function send(Request $request)
    {
        $this->otp->send($this->phone($request));

        return $request->wantsJson()
            ? response()->json(['message' => __('OTP sent to your phone.')])
            : back()->withInput()->with(['ok' => __('OTP sent to your phone.'), 'otp_sent' => true]);
    }

    public function verify(Request $request)
    {
        $phone = $this->phone($request);
        $request->validate(['code' => ['required', 'digits:6']]);

        if (! $this->otp->verify($phone, $request->code)) {
            throw ValidationException::withMessages(['code' => __('Invalid or expired OTP.')]);
        }
        // Passwordless: log in existing user or create a customer account for this phone.
        $user = User::firstOrCreate(['phone' => $phone], ['name' => 'Pet Parent', 'password' => Str::random(32), 'role' => 'customer']);
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
