@extends('dashboard.layout')
@section('title', __('My Pets'))
@section('dash')
<form class="card" method="post" action="{{ $pet->exists ? route('dashboard.pets.update', $pet) : route('dashboard.pets.store') }}">@csrf @if($pet->exists) @method('PUT') @endif
<h3 style="margin-bottom:16px">{{ $pet->exists ? __('Edit') : __('Add Pet') }}</h3>
<div class="fgrid">
<div><label>{{ __('Pet Name') }}</label><input name="name" required value="{{ old('name', $pet->name) }}"></div>
<div><label>{{ __('Species') }}</label><select name="species">@include('partials.pet-select-options', ['selectedPet' => old('species', $pet->species)])</select></div>
<div><label>{{ __('Breed') }}</label><input name="breed" value="{{ old('breed', $pet->breed) }}"></div>
<div><label>{{ __('Age') }}</label><input name="age" value="{{ old('age', $pet->age) }}" placeholder="2 yrs"></div>
<div><label>{{ __('Gender') }}</label><select name="gender"><option value="">—</option><option value="male" @selected(old('gender', $pet->gender) === 'male')>Male</option><option value="female" @selected(old('gender', $pet->gender) === 'female')>Female</option></select></div>
<div><label>{{ __('Weight') }} (kg)</label><input name="weight" type="number" step="0.1" min="0" value="{{ old('weight', $pet->weight) }}"></div>
<div class="full"><label>{{ __('Notes') }}</label><textarea name="notes" rows="3">{{ old('notes', $pet->notes) }}</textarea></div>
</div><button class="btn btn-p" style="margin-top:16px">{{ __('Save') }}</button> <a class="btn btn-o" href="{{ route('dashboard.pets.index') }}">{{ __('Cancel') }}</a></form>
@endsection
