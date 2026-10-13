@extends('layouts.app')
@section('content')
@php($side = ['dashboard' => ['fa-gauge', 'Overview'], 'dashboard.appointments' => ['fa-calendar', 'Appointments'], 'dashboard.pets.index' => ['fa-paw', 'My Pets'], 'dashboard.records' => ['fa-file-medical', 'Medical Records'], 'dashboard.vaccinations' => ['fa-syringe', 'Vaccinations'], 'dashboard.payments' => ['fa-receipt', 'Payments'], 'dashboard.settings' => ['fa-gear', 'Settings']])
<section class="sec alt" style="padding-top:40px"><div class="wrap dash"><aside class="side">
@foreach($side as $r => [$ic, $label])<a href="{{ route($r) }}" @class(['on' => request()->routeIs($r) || ($r === 'dashboard.pets.index' && request()->routeIs('dashboard.pets.*'))])><i class="fa {{ $ic }}"></i><span>{{ __($label) }}</span></a>@endforeach
<form method="post" action="{{ route('logout') }}">@csrf<a href="#" onclick="this.closest('form').submit();return false"><i class="fa fa-right-from-bracket"></i><span>{{ __('Logout') }}</span></a></form>
</aside>
<div style="min-width:0">@include('partials.flash')@yield('dash')</div></div></section>
@endsection
