<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PaymentManager $payments) {}

    public function start(string $code)
    {
        $a = Appointment::where('code', $code)->firstOrFail();
        if ($a->payment_status === 'paid' || $a->payment_method === 'clinic' || $a->status === 'cancelled') {
            return redirect()->route('appointment.success', $code);
        }
        $gateway = PaymentManager::METHOD_GATEWAY[$a->payment_method];

        $payment = Payment::create([
            'appointment_id' => $a->id,
            'user_id' => $a->user_id,
            'gateway' => $gateway,
            'tran_id' => 'TXN'.now()->format('ymdHis').strtoupper(Str::random(6)),
            'amount' => $a->fee,
        ]);

        if (! $this->payments->isConfigured($gateway)) {
            abort_unless(config('payments.simulate'), 503, 'Payment gateway is not configured.');
            return redirect()->route('payment.simulate', $payment->tran_id);
        }

        try {
            return redirect()->away($this->payments->gateway($gateway)->initiate($payment));
        } catch (\Throwable $e) {
            report($e);
            $payment->update(['status' => 'failed']);
            return redirect()->route('appointment.success', $code)->with('err', __('Payment failed or cancelled. You can pay at the clinic.'));
        }
    }

    /** Local/demo stand-in for the gateway page when no API keys are set. */
    public function simulate(Request $request, Payment $payment)
    {
        abort_unless(config('payments.simulate') && ! $this->payments->isConfigured($payment->gateway), 404);
        if ($request->query('result') === 'success') {
            $this->markPaid($payment, ['simulated' => true]);
            return redirect()->route('appointment.success', $payment->appointment->code)->with('ok', __('Payment successful.'));
        }
        if ($request->query('result') === 'fail') {
            $payment->update(['status' => 'failed']);
            return redirect()->route('appointment.success', $payment->appointment->code)->with('err', __('Payment failed or cancelled. You can pay at the clinic.'));
        }

        return view('appointment.simulate', compact('payment'));
    }

    public function success(Request $request, string $gateway)
    {
        $payment = $this->find($request, $gateway);
        if ($payment->status !== 'paid') {
            if ($this->payments->gateway($gateway)->verify($payment, $request)) {
                $this->markPaid($payment);
            } else {
                $payment->update(['status' => 'failed']);
                return redirect()->route('appointment.success', $payment->appointment->code)->with('err', __('Payment failed or cancelled. You can pay at the clinic.'));
            }
        }

        return redirect()->route('appointment.success', $payment->appointment->code)->with('ok', __('Payment successful.'));
    }

    public function fail(Request $request, string $gateway)
    {
        $payment = $this->find($request, $gateway);
        if ($payment->status !== 'paid') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('appointment.success', $payment->appointment->code)->with('err', __('Payment failed or cancelled. You can pay at the clinic.'));
    }

    /** Server-to-server notification (SSLCommerz IPN). */
    public function ipn(Request $request, string $gateway)
    {
        $payment = $this->find($request, $gateway);
        if ($payment->status !== 'paid' && $this->payments->gateway($gateway)->verify($payment, $request)) {
            $this->markPaid($payment);
        }

        return response()->json(['status' => $payment->status]);
    }

    private function find(Request $request, string $gateway): Payment
    {
        abort_unless(in_array($gateway, ['sslcommerz', 'bkash'], true), 404);
        $q = Payment::where('gateway', $gateway);

        return $gateway === 'bkash'
            ? $q->where('gateway_ref', $request->input('paymentID'))->firstOrFail()
            : $q->where('tran_id', $request->input('tran_id'))->firstOrFail();
    }

    private function markPaid(Payment $payment, array $extra = []): void
    {
        $payment->update(['status' => 'paid'] + ($extra ? ['payload' => $extra] : []));
        $payment->appointment->update(['payment_status' => 'paid']);
    }
}
