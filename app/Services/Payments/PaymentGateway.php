<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGateway
{
    /** Start a payment and return the URL the customer must be redirected to. */
    public function initiate(Payment $payment): string;

    /** Verify the gateway callback/IPN; returns true when the payment is confirmed paid. */
    public function verify(Payment $payment, Request $request): bool;
}
