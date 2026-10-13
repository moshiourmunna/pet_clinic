@extends('layouts.app')
@section('title', __('Page not found'))
@section('content')
<section class="sec alt"><div class="wrap" style="text-align:center;max-width:620px"><div style="font-size:90px;color:var(--p);line-height:1"><i class="fa fa-paw"></i></div><h1 style="font-size:64px;margin:10px 0">404</h1><h2 style="margin-bottom:10px">{{ __('Page not found') }}</h2><p style="margin-bottom:24px">{{ __('Still have questions? Call us or chat on WhatsApp.') }}</p><a class="btn btn-p" href="{{ route('home') }}"><i class="fa fa-house"></i>{{ __('Back to Home') }}</a> <a class="btn btn-o" href="{{ route('appointment.create') }}">{{ __('Book Appointment') }}</a></div></section>
@endsection
