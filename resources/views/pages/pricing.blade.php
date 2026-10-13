@extends('layouts.app')
@section('title', __('Pricing & Plans'))
@section('content')
<x-page-header :title="__('Pricing & Plans')"/><section class="sec"><div class="wrap"><div class="grid g3">@foreach($plans as $plan)<x-plan-card :plan="$plan"/>@endforeach</div><div class="card" style="margin-top:40px;overflow-x:auto"><h3 style="margin-bottom:12px">{{ __('Service Price List') }}</h3><table><tr><th><span>{{ __('Service') }}</span></th><th><span>{{ __('Starting From') }}</span></th></tr>@foreach($services as $s)<tr><td><a href=\"{{ route('appointment.create', ['service' => $s->slug]) }}\">{{ $s->name }}</a></td><td>{{ $s->price ? '৳'.number_format($s->price).($s->price_unit ?? '') : '৳—' }}</td></tr>@endforeach</table></div></div></section>@include('partials.cta')
@endsection
