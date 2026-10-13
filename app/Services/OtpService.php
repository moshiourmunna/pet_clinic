<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Services\Sms\SmsManager;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function __construct(private SmsManager $sms) {}

    public function send(string $phone): void
    {
        OtpCode::where('phone', $phone)->delete();
        $code = (string) random_int(100000, 999999);
        OtpCode::create(['phone' => $phone, 'code_hash' => Hash::make($code), 'expires_at' => now()->addMinutes(5)]);
        $this->sms->send($phone, "Your PetCare Clinic login code is {$code}. Valid for 5 minutes.");
    }

    public function verify(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone', $phone)->latest()->first();
        if (! $otp || $otp->expires_at->isPast() || $otp->attempts >= 5) {
            return false;
        }
        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            return false;
        }
        $otp->delete();

        return true;
    }
}
