<?php

namespace App\Services\Sms;

class SmsManager
{
    public function driver(): SmsDriver
    {
        return match (config('sms.driver')) {
            'http' => new HttpSmsDriver(config('sms.http')),
            default => new LogSmsDriver(),
        };
    }

    public function send(string $to, string $message): bool
    {
        return $this->driver()->send($to, $message);
    }
}
