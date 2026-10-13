<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

/**
 * Generic HTTP SMS gateway (works with most BD providers such as BulkSMSBD, SSL Wireless, Alpha SMS).
 * Adjust the parameter names in config/sms.php to match your provider.
 */
class HttpSmsDriver implements SmsDriver
{
    public function __construct(private array $config) {}

    public function send(string $to, string $message): bool
    {
        $p = $this->config['params'];
        $res = Http::timeout(15)->asForm()->post($this->config['url'], [
            $p['api_key'] => $this->config['api_key'],
            $p['sender'] => $this->config['sender_id'],
            $p['to'] => $to,
            $p['message'] => $message,
        ]);

        return $res->successful();
    }
}
