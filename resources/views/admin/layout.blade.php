@extends('layouts.app')
@push('head')<meta name="robots" content="noindex">@endpush
@section('content')
<section class="sec alt" style="padding-top:34px"><div class="wrap dash" style="max-width:1320px"><aside class="side">
<div style="color:#fff;font-weight:700;padding:6px 14px 12px;font-size:13px;letter-spacing:.5px">ADMIN PANEL</div>
<a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])><i class="fa fa-gauge"></i><span>Dashboard</span></a>
<a href="{{ route('admin.appointments.index') }}" @class(['on' => request()->routeIs('admin.appointments.*')])><i class="fa fa-calendar-check"></i><span>Appointments</span></a>
@foreach(\App\Support\AdminResources::all() as $key => $r)
<a href="{{ route('admin.content.index', $key) }}" @class(['on' => request()->route('resource') === $key || ($key === 'doctors' && request()->routeIs('admin.doctors.*'))])><i class="fa {{ $r['icon'] }}"></i><span>{{ $r['label'] }}</span></a>
@endforeach
<a href="{{ route('admin.messages.index') }}" @class(['on' => request()->routeIs('admin.messages.*')])><i class="fa fa-envelope"></i><span>Messages</span></a>
<a href="{{ route('admin.settings') }}" @class(['on' => request()->routeIs('admin.settings')])><i class="fa fa-gear"></i><span>Settings</span></a>
<form method="post" action="{{ route('logout') }}">@csrf<a href="#" onclick="this.closest('form').submit();return false"><i class="fa fa-right-from-bracket"></i><span>Logout</span></a></form>
</aside><div style="min-width:0">@include('partials.flash')@yield('admin')</div></div></section>
@endsection
