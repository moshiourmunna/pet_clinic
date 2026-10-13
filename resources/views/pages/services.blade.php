@extends('layouts.app')
@section('title', __('Our Services'))
@section('description', __('Preventive, diagnostic, surgical and wellness care.'))
@section('content')
<x-page-header :title="__('Our Services')"/><section class="sec"><div class="wrap"><div class="sh"><span class="tag">{{ __('20+ Services') }}</span><h2>{{ __('Everything Your Pet Needs') }}</h2><p>{{ __('Preventive, diagnostic, surgical and wellness care.') }}</p></div><div class="grid g4">@foreach($services as $service)<x-service-card :service="$service"/>@endforeach</div></div></section>@include('partials.cta')
@endsection
