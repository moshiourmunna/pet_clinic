@extends('layouts.app')
@section('title', $doctor->name)
@section('description', $doctor->bio)
@section('og_image', $doctor->photo_url)
@section('content')
<x-page-header :title="__('Doctor Profile')"><a href="{{ route('doctors.index') }}">{{ __('Doctors') }}</a> / </x-page-header>
<section class="sec"><div class="wrap book-layout"><div>
<div class="card" style="display:grid;grid-template-columns:240px 1fr;gap:26px;align-items:center" id="dp"><img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}" style="border-radius:18px;aspect-ratio:1;object-fit:cover;object-position:top"><div><h2>{{ $doctor->name }}</h2><div style="color:var(--p);font-weight:600"><span>{{ $doctor->degree }} · {{ $doctor->title }}</span></div><p style="margin:12px 0">{{ $doctor->bio }}</p><div style="display:flex;gap:20px;flex-wrap:wrap;font-size:14px"><span><i class="fa fa-star" style="color:#F5B301"></i> {{ number_format($doctor->rating, 1) }} ({{ $doctor->reviews }})</span><span><i class="fa fa-paw" style="color:var(--p)"></i> {{ number_format($doctor->patients) }}+ <span>{{ __('patients') }}</span></span><span><i class="fa fa-money-bill" style="color:var(--p)"></i> ৳{{ number_format($doctor->fee) }}</span>@if($doctor->bvc_reg)<span><i class="fa fa-id-card" style="color:var(--p)"></i> {{ $doctor->bvc_reg }}</span>@endif</div></div></div>
<div class="card" style="margin-top:22px;overflow-x:auto"><h3>{{ __('Weekly Schedule') }}</h3><table style="margin-top:12px"><tr><th><span>{{ __('Day') }}</span></th><th><span>{{ __('Time') }}</span></th><th><span>{{ __('Status') }}</span></th></tr>
@foreach([6, 0, 1, 2, 3, 4, 5] as $dow)
@php($rows = $doctor->schedules->where('day_of_week', $dow))
<tr><td>{{ __(\App\Models\DoctorSchedule::DAYS[$dow]) }}</td><td>@forelse($rows as $r){{ \Carbon\Carbon::parse($r->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('g:i A') }}@if(! $loop->last), @endif @empty — @endforelse</td><td>@if($rows->count())<span class="pill ok"><span>{{ __('Available') }}</span></span>@else<span class="pill wait"><span>{{ __('Off') }}</span></span>@endif</td></tr>
@endforeach
</table></div></div>
<div class="card summary" id="quick-book" data-slots-url="{{ route('api.slots', $doctor) }}"><h3>{{ __('Quick Booking') }}</h3><label for="qb-date" style="margin-top:14px"><span>{{ __('Date') }}</span></label><input id="qb-date" type="date" min="{{ today()->toDateString() }}" value="{{ today()->addDay()->toDateString() }}"><label style="margin-top:14px"><span>{{ __('Available Slots') }}</span></label><div class="slots" id="qb-slots"></div><a class="btn btn-p" id="qb-go" style="width:100%;justify-content:center;margin-top:18px" href="{{ route('appointment.create', ['doctor' => $doctor->slug]) }}" data-base="{{ route('appointment.create', ['doctor' => $doctor->slug]) }}"><span>{{ __('Continue Booking') }}</span></a></div>
</div></section><style>@media(max-width:820px){#dp{grid-template-columns:1fr!important}}</style>
@endsection
