@extends('admin.layout')
@section('title', 'Settings')
@section('admin')
<form class="card" method="post" action="{{ route('admin.settings') }}">@csrf @method('PUT')<h2 style="margin-bottom:16px">Clinic settings</h2>
<div class="fgrid">@foreach(\App\Models\Setting::DEFAULTS as $k => $default)<div><label for="s-{{ $k }}">{{ \Illuminate\Support\Str::headline(str_replace(['_en', '_bn'], [' (English)', ' (বাংলা)'], $k)) }}</label><input id="s-{{ $k }}" name="{{ $k }}" value="{{ old($k, $settings[$k] ?? '') }}"></div>@endforeach</div>
<button class="btn btn-p" style="margin-top:18px">Save settings</button></form>
@endsection
