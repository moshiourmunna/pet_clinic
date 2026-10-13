@extends('dashboard.layout')
@section('title', __('My Pets'))
@section('dash')
<div class="admin-top"><h2>{{ __('My Pets') }}</h2><a class="btn btn-p" href="{{ route('dashboard.pets.create') }}"><i class="fa fa-plus"></i>{{ __('Add Pet') }}</a></div>
<div class="grid g3">
@forelse($pets as $pet)
<div class="card"><div style="display:flex;gap:14px;align-items:center"><div class="stat"><i class="fa {{ ['Dog' => 'fa-dog', 'Cat' => 'fa-cat', 'Bird' => 'fa-dove', 'Rabbit' => 'fa-carrot'][$pet->species] ?? 'fa-paw' }}"></i></div><div><h3>{{ $pet->name }}</h3><small>{{ __($pet->species) }} · {{ $pet->breed }} · {{ $pet->age }}</small></div></div>
<p style="font-size:13.5px;margin:10px 0">{{ $pet->weight ? $pet->weight.' kg · ' : '' }}{{ $pet->appointments_count }} {{ __('Appointments') }}</p>
<a class="btn btn-o btn-sm" href="{{ route('dashboard.pets.edit', $pet) }}">{{ __('Edit') }}</a>
<form class="inline" method="post" action="{{ route('dashboard.pets.destroy', $pet) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-d btn-sm">{{ __('Delete') }}</button></form></div>
@empty<p>{{ __('No records yet.') }}</p>@endforelse
</div>
@endsection
