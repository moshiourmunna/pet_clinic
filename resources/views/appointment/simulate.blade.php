@extends('layouts.app')
@section('title', 'Payment (demo)')
@section('content')
<section class="sec alt"><div class="wrap" style="max-width:520px"><div class="card" style="padding:34px;text-align:center">
<div class="tag">Sandbox / demo gateway</div>
<h2 style="margin-bottom:6px">{{ $payment->gateway === 'bkash' ? 'bKash' : 'SSLCommerz' }}</h2>
<p>No API keys configured, so this page simulates the gateway. Add keys in <code>.env</code> to use the real sandbox.</p>
<p style="font-size:30px;font-weight:800;color:var(--n);margin:16px 0">৳{{ number_format($payment->amount) }}</p>
<p style="font-size:13px">{{ $payment->tran_id }} · {{ $payment->appointment->code }}</p>
<div style="display:flex;gap:10px;justify-content:center;margin-top:20px"><a class="btn btn-p" href="{{ route('payment.simulate', [$payment->tran_id, 'result' => 'success']) }}">Pay successfully</a><a class="btn btn-o" href="{{ route('payment.simulate', [$payment->tran_id, 'result' => 'fail']) }}">Fail / cancel</a></div>
</div></div></section>
@endsection
