@extends('layouts.app')
@section('title', __('Book an Appointment'))
@section('description', __('Choose a doctor and time, or just send us a request.'))
@section('content')
@php($type = old('type', 'slot'))
@php($pay = old('payment_method', 'clinic'))
@php($docSel = (int) old('doctor_id', $selected['doctor']))
@php($u = auth()->user())
<x-page-header :title="__('Book an Appointment')"/>
<section class="sec alt"><div class="wrap book-layout">
<form class="card" style="padding:30px" method="post" action="{{ route('appointment.store') }}" id="book-form" novalidate data-slots-url="{{ url('/api/doctors') }}">
@csrf
@include('partials.flash')
<div class="stepper"><div class="on">1. <span>{{ __('Booking Type') }}</span></div><div class="on">2. <span>{!! __('Pet & Service') !!}</span></div><div class="on only-slot">3. <span>{!! __('Doctor & Time') !!}</span></div><div data-step-pay @class(['on' => $pay !== 'clinic'])>4. <span>{{ __('Payment') }}</span></div></div>

<div class="mode choice" data-mode="1">
<label @class(['on' => $type === 'slot']) data-v="slot"><input type="radio" name="type" value="slot" @checked($type === 'slot')><div><b><span>{!! __('Choose Doctor & Time') !!}</span></b><span>{{ __('Instant confirmed booking with your preferred vet.') }}</span></div></label>
<label @class(['on' => $type === 'request']) data-v="request"><input type="radio" name="type" value="request" @checked($type === 'request')><div><b><span>{{ __('Send a Request') }}</span></b><span>{{ __('Tell us what you need; we will call to confirm.') }}</span></div></label>
</div>

<h3 style="margin-bottom:14px">{!! __('Owner & Pet Details') !!}</h3>
<div class="fgrid">
<div><label for="b-name"><span>{{ __('Your Name') }}</span> *</label><input id="b-name" name="owner_name" required maxlength="100" value="{{ old('owner_name', $u?->name) }}" placeholder="{{ __('Full name') }}" @class(['is-invalid' => $errors->has('owner_name')])>@error('owner_name')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="b-phone"><span>{{ __('Phone') }}</span> *</label><input id="b-phone" name="phone" required inputmode="tel" value="{{ old('phone', $u?->phone ?? $selected['phone']) }}" placeholder="01XXXXXXXXX" @class(['is-invalid' => $errors->has('phone')])>@error('phone')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="b-email">{{ __('Email') }} <small style="font-weight:400">({{ __('Optional') }})</small></label><input id="b-email" type="email" name="email" value="{{ old('email', $u?->email) }}" placeholder="you@example.com">@error('email')<span class="err">{{ $message }}</span>@enderror</div>
@if($pets->count())
<div><label for="b-mypet">{{ __('My Pets') }}</label><select id="b-mypet" name="pet_id"><option value="">—</option>@foreach($pets as $p)<option value="{{ $p->id }}" data-name="{{ $p->name }}" data-type="{{ $p->species }}" data-ab="{{ trim($p->age.', '.$p->breed, ', ') }}" @selected(old('pet_id') == $p->id)>{{ $p->name }} ({{ __($p->species) }})</option>@endforeach</select></div>
@else <div></div> @endif
<div><label for="b-pet"><span>{{ __('Pet Name') }}</span> *</label><input id="b-pet" name="pet_name" required maxlength="60" value="{{ old('pet_name') }}" placeholder="e.g. Bruno" @class(['is-invalid' => $errors->has('pet_name')])>@error('pet_name')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="b-type"><span>{{ __('Pet Type') }}</span></label><select id="b-type" name="pet_type">@include('partials.pet-select-options', ['selectedPet' => old('pet_type', $selected['pet_type'])])</select></div>
<div><label for="b-ab"><span>{{ __('Age / Breed') }}</span></label><input id="b-ab" name="pet_age_breed" value="{{ old('pet_age_breed') }}" placeholder="2 yrs, Labrador"></div>
<div><label for="b-svc"><span>{{ __('Service') }}</span></label><select id="b-svc" name="service_id">@foreach($services as $s)<option value="{{ $s->id }}" data-price="{{ $s->price }}" @selected(old('service_id', $selected['service']) == $s->id)>{{ $s->name }}</option>@endforeach</select></div>
<div class="full"><label for="b-sym"><span>{{ __('Problem / Symptoms') }}</span></label><textarea id="b-sym" name="symptoms" rows="3" placeholder="{{ __('Describe briefly...') }}">{{ old('symptoms') }}</textarea></div>
</div>

<div class="only-slot" @if($type !== 'slot') style="display:none" @endif><h3 style="margin:26px 0 14px">{{ __('Select Doctor') }}</h3>
<div class="pickdoc choice">
@foreach($doctors as $d)
<label @class(['on' => $docSel === $d->id]) data-fee="{{ $d->fee }}" data-name="{{ $d->name }}"><input type="radio" name="doctor_id" value="{{ $d->id }}" @checked($docSel === $d->id) hidden><img src="{{ $d->photo_url }}" alt=""><b style="color:var(--n);font-size:14px;display:block"><span>{{ $d->name }}</span></b><small><span>{{ $d->title }}</span></small></label>
@endforeach
</div>@error('doctor_id')<span class="err">{{ $message }}</span>@enderror
</div>
<div class="fgrid" style="margin-top:20px">
<div><label for="b-date"><span>{{ __('Date') }}</span></label><input id="b-date" type="date" name="date" required min="{{ today()->toDateString() }}" max="{{ today()->addMonths(3)->subDay()->toDateString() }}" value="{{ old('date', $selected['date']) }}">@error('date')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="b-visit"><span>{{ __('Visit Type') }}</span></label><select id="b-visit" name="visit_type">@foreach(\App\Models\Appointment::VISIT_TYPES as $k => $v)<option value="{{ $k }}" @selected(old('visit_type') === $k)>{{ __($v) }}</option>@endforeach</select></div>
</div>
<div class="only-slot" @if($type !== 'slot') style="display:none" @endif>
<label style="margin-top:18px"><span>{{ __('Available Time Slots') }}</span></label>
<input type="hidden" name="time" id="b-time" value="{{ old('time', $selected['time']) }}">
<div class="slots" id="slots" aria-live="polite"><span class="msg">{{ __('Select a date to see available slots.') }}</span></div>@error('time')<span class="err">{{ $message }}</span>@enderror
</div>

<h3 style="margin:26px 0 6px">{{ __('Payment') }} <small style="font-weight:500;color:var(--t)">(<span>{{ __('optional') }}</span>)</small></h3><p style="font-size:14px;margin-bottom:12px">{{ __('Pay now to secure your slot, or skip and pay at the clinic.') }}</p>
<div class="pay choice">
<label @class(['on' => $pay === 'clinic']) data-label="{{ __('At clinic') }}"><input type="radio" name="payment_method" value="clinic" hidden @checked($pay === 'clinic')><i class="fa fa-store"></i><span>{{ __('Pay at Clinic') }}</span></label>
<label @class(['on' => $pay === 'bkash_nagad']) data-label="bKash / Nagad"><input type="radio" name="payment_method" value="bkash_nagad" hidden @checked($pay === 'bkash_nagad')><i class="fa fa-mobile-screen"></i>bKash / Nagad</label>
<label @class(['on' => $pay === 'card']) data-label="Card"><input type="radio" name="payment_method" value="card" hidden @checked($pay === 'card')><i class="fa fa-credit-card"></i><span>{{ __('Card / SSLCommerz') }}</span></label>
</div>
<label style="display:flex;gap:8px;align-items:center;margin-top:20px;font-weight:500"><input type="checkbox" name="terms" value="1" style="width:auto" @checked(old('terms', true))><span>{!! __('I agree to the terms & cancellation policy') !!}</span></label>@error('terms')<span class="err">{{ $message }}</span>@enderror
<button class="btn btn-a" style="margin-top:20px;width:100%;justify-content:center;font-size:16px;padding:15px"><i class="fa fa-calendar-check"></i><span>{{ __('Confirm Booking') }}</span></button>
</form>

<aside class="card summary" id="summary"><h3>{{ __('Booking Summary') }}</h3><div style="margin-top:12px">
<div class="row"><span><span>{{ __('Service') }}</span></span><b data-sum="service">—</b></div>
<div class="row only-slot"><span><span>{{ __('Doctor') }}</span></span><b data-sum="doctor">—</b></div>
<div class="row"><span><span>{{ __('Date') }}</span></span><b data-sum="date">—</b></div>
<div class="row only-slot"><span><span>{{ __('Time') }}</span></span><b data-sum="time">—</b></div>
<div class="row"><span><span>{{ __('Fee') }}</span></span><b data-sum="fee">—</b></div>
<div class="row"><span><span>{{ __('Payment') }}</span></span><b data-sum="pay">—</b></div></div>
<div style="background:var(--pl);border-radius:12px;padding:14px;margin-top:16px;font-size:13.5px"><i class="fa fa-circle-info" style="color:var(--p)"></i> <span>{!! __('You will receive SMS & email confirmation. Need urgent help? Call our 24/7 emergency line.') !!}</span></div><a class="btn btn-o" style="width:100%;justify-content:center;margin-top:14px" href="tel:{{ $settings['emergency_phone'] }}"><i class="fa fa-phone"></i>{{ $settings['phone'] }}</a></aside>
</div></section>
@endsection
@push('scripts')<script src="{{ asset('assets/booking.js') }}"></script>@endpush
