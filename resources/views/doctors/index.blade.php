@extends('layouts.app')
@section('title', __('Our Doctors'))
@section('description', __('Highly qualified, compassionate vets dedicated to your pet.'))
@section('content')
<x-page-header :title="__('Our Doctors')"/>
<section class="sec"><div class="wrap">
<form class="card" id="doc-filter" action="{{ route('doctors.index') }}" method="get" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:30px;padding:16px">
<input name="q" value="{{ request('q') }}" style="flex:2;min-width:200px" placeholder="{{ __('Search doctor by name...') }}" aria-label="Search">
<select name="speciality" style="flex:1;min-width:160px" aria-label="Speciality"><option value="">{{ __('All Specialities') }}</option>@foreach(\App\Models\Doctor::SPECIALITIES as $k => $v)<option value="{{ $k }}" @selected(request('speciality') === $k)>{{ __($v) }}</option>@endforeach</select>
<select name="day" style="flex:1;min-width:140px" aria-label="Day"><option value="">{{ __('Any Day') }}</option><option value="today" @selected(request('day') === 'today')>{{ __('Today') }}</option><option value="tomorrow" @selected(request('day') === 'tomorrow')>{{ __('Tomorrow') }}</option></select>
<noscript><button class="btn btn-p">Search</button></noscript>
</form>
<div class="grid g4" id="doc-grid">@include('doctors._grid')</div>
</div></section>
@include('partials.cta')
@endsection
