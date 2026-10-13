@extends('layouts.app')
@section('title', __('Register'))
@section('content')
<section class="sec alt"><div class="wrap" style="max-width:520px"><form class="card" style="padding:34px" method="post" action="{{ route('register') }}">@csrf
<h2 style="text-align:center;margin-bottom:18px">{{ __('Create account') }}</h2>
@include('partials.flash')
<div class="fgrid">
<div class="full"><label for="r-name">{{ __('Full Name') }}</label><input id="r-name" name="name" required value="{{ old('name') }}"></div>
<div><label for="r-phone">{{ __('Phone') }}</label><input id="r-phone" name="phone" required inputmode="tel" placeholder="01XXXXXXXXX" value="{{ old('phone') }}">@error('phone')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="r-email">{{ __('Email') }} <small style="font-weight:400">({{ __('Optional') }})</small></label><input id="r-email" type="email" name="email" value="{{ old('email') }}">@error('email')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="r-pw">{{ __('Password') }}</label><input id="r-pw" type="password" name="password" required minlength="8" autocomplete="new-password">@error('password')<span class="err">{{ $message }}</span>@enderror</div>
<div><label for="r-pw2">{{ __('Confirm Password') }}</label><input id="r-pw2" type="password" name="password_confirmation" required></div>
</div>
<button class="btn btn-p" style="width:100%;justify-content:center;margin-top:20px">{{ __('Register') }}</button>
<p style="text-align:center;margin-top:16px;font-size:14px">{{ __('Already have an account?') }} <a style="color:var(--p);font-weight:600" href="{{ route('login') }}">{{ __('Login') }}</a></p>
</form></div></section>
@endsection
