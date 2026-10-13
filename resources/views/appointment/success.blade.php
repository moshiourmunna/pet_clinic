@extends('layouts.app')
@section('title', __('Booking received!'))
@section('content')
@php($a = $appointment)
<x-page-header :title="__('Booking received!')"/>
<section class="sec alt"><div class="wrap" style="max-width:720px"><div class="card" style="padding:34px">
@include('partials.flash')
<div style="text-align:center;margin-bottom:20px"><div style="width:72px;height:72px;border-radius:50%;background:var(--pl);color:var(--p);display:grid;place-items:center;font-size:32px;margin:0 auto 12px"><i class="fa fa-calendar-check"></i></div>
<h2>{{ $a->type === 'slot' ? __('Confirmed') : __('Requested') }}</h2><p>{{ __('Booking Code') }}: <b style="color:var(--n);font-size:18px">{{ $a->code }}</b></p></div>
<div class="summary" style="position:static">
<div class="row"><span>{{ __('Service') }}</span><b>{{ $a->service->name }}</b></div>
<div class="row"><span>{{ __('Doctor') }}</span><b>{{ $a->doctor?->name ?? '—' }}</b></div>
<div class="row"><span>{{ __('Date') }}</span><b>{{ $a->date->format('d M Y') }}{{ $a->time ? ', '.$a->time_label : '' }}</b></div>
<div class="row"><span>{{ __('Pet Name') }}</span><b>{{ $a->pet_name }} ({{ __($a->pet_type) }})</b></div>
<div class="row"><span>{{ __('Fee') }}</span><b>৳{{ number_format($a->fee) }}</b></div>
<div class="row"><span>{{ __('Payment') }}</span><b><span class="pill {{ $a->payment_status === 'paid' ? 'paid' : 'wait' }}">{{ $a->payment_status === 'paid' ? __('Paid') : __(\App\Models\Appointment::PAYMENT_METHODS[$a->payment_method]) }}</span></b></div>
</div>
<p style="margin-top:16px;font-size:14px">{!! __('You will receive SMS & email confirmation. Need urgent help? Call our 24/7 emergency line.') !!}</p>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px">
@if($a->payment_method !== 'clinic' && $a->payment_status !== 'paid' && $a->status !== 'cancelled')<a class="btn btn-a" href="{{ route('payment.start', $a->code) }}"><i class="fa fa-credit-card"></i>{{ __('Pay Now') }}</a>@endif
@auth<a class="btn btn-p" href="{{ route('dashboard.appointments') }}">{{ __('My Appointments') }}</a>@else<a class="btn btn-p" href="{{ route('register') }}">{{ __('Create account') }}</a>@endauth
<a class="btn btn-o" href="{{ route('home') }}">{{ __('Back to Home') }}</a></div>
</div></div></section>
@endsection
