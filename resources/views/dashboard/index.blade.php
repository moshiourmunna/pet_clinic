@extends('dashboard.layout')
@section('title', __('Dashboard'))
@section('dash')
<h2 style="margin-bottom:18px"><span>{{ app()->getLocale() === 'bn' ? 'হ্যালো' : 'Hello' }}, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }} 👋</span></h2>
<div class="grid g4" style="margin-bottom:24px"><div class="card stat"><i class="fa fa-calendar-check"></i><div><b>{{ $stats['upcoming'] }}</b><small><span>{{ __('Upcoming') }}</span></small></div></div><div class="card stat"><i class="fa fa-paw"></i><div><b>{{ $stats['pets'] }}</b><small><span>{{ __('Pets') }}</span></small></div></div><div class="card stat"><i class="fa fa-syringe"></i><div><b>{{ $stats['vaccine_due'] }}</b><small><span>{{ __('Vaccine Due') }}</span></small></div></div><div class="card stat"><i class="fa fa-receipt"></i><div><b>৳{{ number_format($stats['paid']) }}</b><small><span>{{ __('Paid') }}</span></small></div></div></div>
<div class="card" style="overflow-x:auto"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><h3>{{ __('My Appointments') }}</h3><a class="btn btn-p" style="padding:9px 16px" href="{{ route('appointment.create') }}">+ <span>{{ __('New') }}</span></a></div>
@include('dashboard._appt_table')</div>
@endsection
