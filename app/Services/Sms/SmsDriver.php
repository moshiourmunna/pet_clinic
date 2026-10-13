<?php

namespace App\Services\Sms;

interface SmsDriver
{
    public function send(string $to, string $message): bool;
}
