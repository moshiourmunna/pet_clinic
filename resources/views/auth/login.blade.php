@extends('layouts.app')
@section('title', __('Login'))
@section('content')
<section class="sec alt"><div class="wrap" style="max-width:460px"><div class="card" style="padding:34px">
<h2 style="text-align:center">{{ __('Welcome Back') }}</h2><p style="text-align:center;margin-bottom:22px">{!! __('Log in to manage bookings & pet records') !!}</p>
@include('partials.flash')
<div class="mode choice" style="grid-template-columns:1fr 1fr;margin-bottom:18px" id="login-tabs">
<label @class(['on' => ! session('otp_sent') && ! $errors->has('code')]) data-tab="pw" style="padding:10px;justify-content:center"><input type="radio" name="tab" hidden><b style="font-size:14px"><i class="fa fa-key"></i> {{ __('Password') }}</b></label>
<label @class(['on' => session('otp_sent') || $errors->has('code')]) data-tab="otp" style="padding:10px;justify-content:center"><input type="radio" name="tab" hidden><b style="font-size:14px"><i class="fa fa-mobile-screen"></i> OTP</b></label>
</div>
<form method="post" action="{{ route('login') }}" data-pane="pw" @if(session('otp_sent') || $errors->has('code')) style="display:none" @endif>@csrf
<label for="l-login"><span>{{ __('Phone or Email') }}</span></label><input id="l-login" name="login" required value="{{ old('login') }}" autocomplete="username">
<label for="l-pw" style="margin-top:14px"><span>{{ __('Password') }}</span></label><input id="l-pw" type="password" name="password" required autocomplete="current-password">
<label style="display:flex;gap:8px;align-items:center;margin-top:12px;font-weight:500"><input type="checkbox" name="remember" style="width:auto"> Remember me</label>
<button class="btn btn-p" style="width:100%;justify-content:center;margin-top:20px"><span>{{ __('Login') }}</span></button>
</form>
<div data-pane="otp" @unless(session('otp_sent') || $errors->has('code')) style="display:none" @endunless>
<form method="post" action="{{ route('otp.send') }}">@csrf<label for="o-phone">{{ __('Phone') }}</label><div style="display:flex;gap:8px"><input id="o-phone" name="phone" required inputmode="tel" placeholder="01XXXXXXXXX" value="{{ old('phone') }}"><button class="btn btn-o" style="padding:10px 14px"><i class="fa fa-mobile-screen"></i>{{ __('Send OTP') }}</button></div></form>
<form method="post" action="{{ route('otp.verify') }}" style="margin-top:14px">@csrf<input type="hidden" name="phone" value="{{ old('phone') }}"><label for="o-code">{{ __('Enter OTP') }}</label><input id="o-code" name="code" inputmode="numeric" maxlength="6" placeholder="••••••" required><button class="btn btn-p" style="width:100%;justify-content:center;margin-top:14px">{!! __('Verify & Login') !!}</button></form>
</div>
<p style="text-align:center;margin-top:16px;font-size:14px"><span>{{ __('New here?') }}</span> <a style="color:var(--p);font-weight:600" href="{{ route('register') }}"><span>{{ __('Create account') }}</span></a></p>
</div></div></section>
@endsection
@push('scripts')<script>document.querySelectorAll('#login-tabs label').forEach(l=>l.addEventListener('click',()=>document.querySelectorAll('[data-pane]').forEach(p=>p.style.display=p.dataset.pane===l.dataset.tab?'':'none')));</script>@endpush
