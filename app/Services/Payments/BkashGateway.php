<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/** bKash Tokenized Checkout (create → customer approves → execute on callback). */
class BkashGateway implements PaymentGateway
{
    public function __construct(private array $cfg) {}

    private function base(): string
    {
        return $this->cfg['sandbox'] ? $this->cfg['sandbox_url'] : $this->cfg['live_url'];
    }

    private function token(): string
    {
        return Cache::remember('bkash_id_token', 3000, function () {
            $res = Http::withHeaders([
                'username' => $this->cfg['username'],
                'password' => $this->cfg['password'],
            ])->post($this->base().'/tokenized/checkout/token/grant', [
                'app_key' => $this->cfg['app_key'],
                'app_secret' => $this->cfg['app_secret'],
            ]);

            return $res->json('id_token') ?? throw new RuntimeException('bKash token failed: '.$res->body());
        });
    }

    private function client()
    {
        return Http::withHeaders(['Authorization' => $this->token(), 'X-APP-Key' => $this->cfg['app_key']])->timeout(30);
    }

    public function initiate(Payment $payment): string
    {
        $res = $this->client()->post($this->base().'/tokenized/checkout/create', [
            'mode' => '0011',
            'payerReference' => $payment->appointment->phone,
            'callbackURL' => route('payment.callback', 'bkash'),
            'amount' => (string) $payment->amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $payment->tran_id,
        ]);
        if (! $res->json('bkashURL')) {
            throw new RuntimeException('bKash create failed: '.$res->body());
        }
        $payment->update(['gateway_ref' => $res->json('paymentID'), 'payload' => $res->json()]);

        return $res->json('bkashURL');
    }

    public function verify(Payment $payment, Request $request): bool
    {
        if ($request->input('status') !== 'success') return false;

        $res = $this->client()->post($this->base().'/tokenized/checkout/execute', ['paymentID' => $request->input('paymentID')]);
        $payment->update(['payload' => $res->json()]);

        return $res->json('transactionStatus') === 'Completed' && $res->json('merchantInvoiceNumber') === $payment->tran_id;
    }
}
