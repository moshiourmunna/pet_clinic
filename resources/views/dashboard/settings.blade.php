@extends('dashboard.layout')
@section('title', __('Settings'))
@section('dash')
<form class="card" method="post" action="{{ route('dashboard.settings') }}">@csrf @method('PUT')<h3 style="margin-bottom:16px">{{ __('Settings') }}</h3>
<div class="fgrid"><div><label>{{ __('Full Name') }}</label><input name="name" required value="{{ old('name', $user->name) }}"></div><div><label>{{ __('Phone') }}</label><input name="phone" required value="{{ old('phone', $user->phone) }}"></div><div><label>{{ __('Email') }}</label><input type="email" name="email" value="{{ old('email', $user->email) }}"></div><div><label>{{ __('Address') }}</label><input name="address" value="{{ old('address', $user->address) }}"></div><div><label>{{ __('Password') }}</label><input type="password" name="password" autocomplete="new-password"></div><div><label>{{ __('Confirm Password') }}</label><input type="password" name="password_confirmation"></div></div>
<button class="btn btn-p" style="margin-top:16px">{{ __('Save') }}</button></form>
@endsection
