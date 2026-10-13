<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class LogSmsDriver implements SmsDriver
{
    public function send(string $to, string $message): bool
    {
        Log::channel(config('sms.log_channel', 'stack'))->info("[SMS to {$to}] {$message}");

        return true;
    }
}
