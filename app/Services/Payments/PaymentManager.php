<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class PaymentManager
{
    public const METHOD_GATEWAY = ['card' => 'sslcommerz', 'bkash_nagad' => 'bkash'];

    public function gateway(string $name): PaymentGateway
    {
        return match ($name) {
            'sslcommerz' => new SslCommerzGateway(config('payments.sslcommerz')),
            'bkash' => new BkashGateway(config('payments.bkash')),
            default => throw new InvalidArgumentException("Unknown gateway {$name}"),
        };
    }

    public function isConfigured(string $name): bool
    {
        return match ($name) {
            'sslcommerz' => filled(config('payments.sslcommerz.store_id')),
            'bkash' => filled(config('payments.bkash.app_key')),
            default => false,
        };
    }
}
