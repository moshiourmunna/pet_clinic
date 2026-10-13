<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/** SSLCommerz v4 hosted checkout (cards, mobile banking, net banking). */
class SslCommerzGateway implements PaymentGateway
{
    public function __construct(private array $cfg) {}

    private function base(): string
    {
        return $this->cfg['sandbox'] ? $this->cfg['sandbox_url'] : $this->cfg['live_url'];
    }

    public function initiate(Payment $payment): string
    {
        $a = $payment->appointment;
        $res = Http::asForm()->timeout(30)->post($this->base().'/gwprocess/v4/api.php', [
            'store_id' => $this->cfg['store_id'],
            'store_passwd' => $this->cfg['store_password'],
            'total_amount' => $payment->amount,
            'currency' => $payment->currency,
            'tran_id' => $payment->tran_id,
            'success_url' => route('payment.success', 'sslcommerz'),
            'fail_url' => route('payment.fail', 'sslcommerz'),
            'cancel_url' => route('payment.cancel', 'sslcommerz'),
            'ipn_url' => route('payment.ipn', 'sslcommerz'),
            'cus_name' => $a->owner_name,
            'cus_email' => $a->email ?: 'customer@example.com',
            'cus_phone' => $a->phone,
            'cus_add1' => 'Dhaka', 'cus_city' => 'Dhaka', 'cus_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'product_name' => 'Vet appointment '.$a->code,
            'product_category' => 'Service',
            'product_profile' => 'non-physical-goods',
            'value_a' => $a->code,
        ]);

        $url = $res->json('GatewayPageURL');
        if (! $url) {
            throw new RuntimeException('SSLCommerz init failed: '.$res->body());
        }
        $payment->update(['payload' => $res->json()]);

        return $url;
    }

    public function verify(Payment $payment, Request $request): bool
    {
        $valId = $request->input('val_id');
        if (! $valId) return false;

        $res = Http::timeout(30)->get($this->base().'/validator/api/validationserverAPI.php', [
            'val_id' => $valId,
            'store_id' => $this->cfg['store_id'],
            'store_passwd' => $this->cfg['store_password'],
            'format' => 'json',
        ]);
        $ok = in_array($res->json('status'), ['VALID', 'VALIDATED'], true)
            && $res->json('tran_id') === $payment->tran_id
            && (float) $res->json('amount') >= (float) $payment->amount;
        $payment->update(['gateway_ref' => $res->json('bank_tran_id'), 'payload' => $res->json()]);

        return $ok;
    }
}
