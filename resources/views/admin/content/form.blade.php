@extends('admin.layout')
@section('title', $cfg['label'])
@section('admin')
<form class="card" method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.content.update', [$resource, $item->id]) : route('admin.content.store', $resource) }}">@csrf @if($item->exists) @method('PUT') @endif
<div class="admin-top"><h2>{{ $item->exists ? 'Edit' : 'Add' }} — {{ $cfg['label'] }}</h2><a class="btn btn-o btn-sm" href="{{ route('admin.content.index', $resource) }}">← Back</a></div>
<p style="font-size:13px;margin-bottom:16px"><i class="fa fa-language" style="color:var(--p)"></i> Fill English, বাংলা or both. If only one language is given, it is shown for both languages on the site.</p>
<div class="fgrid">
@foreach($cfg['fields'] as $name => $f)
@php($raw = $item->exists ? $item->getRawOriginal($name) : null)
@php($val = old($name, $f[0] === 'lines' ? implode("\n", (array) ($item->{$name} ?? [])) : $raw))
<div @class(['full' => in_array($f[0], ['textarea', 'lines'])])>
<label for="f-{{ $name }}">{{ $f[1] }} @if(($f[2] ?? null) === 'required')*@endif</label>
@switch($f[0])
@case('textarea')<textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ str_starts_with($name, 'body') ? 8 : 3 }}">{{ $val }}</textarea>@break
@case('lines')<textarea id="f-{{ $name }}" name="{{ $name }}" rows="5">{{ $val }}</textarea>@break
@case('number')<input id="f-{{ $name }}" type="number" step="any" name="{{ $name }}" value="{{ $val }}">@break
@case('checkbox')<input type="hidden" name="{{ $name }}" value="0"><input id="f-{{ $name }}" type="checkbox" name="{{ $name }}" value="1" style="width:auto" @checked(old($name, $item->exists ? $raw : true))>@break
@case('select')<select id="f-{{ $name }}" name="{{ $name }}">@foreach($f[3] as $k => $v)<option value="{{ $k }}" @selected($val === $k)>{{ $v }}</option>@endforeach</select>@break
@case('image')@if($raw)<img src="{{ str_starts_with($raw, 'assets/') ? asset($raw) : asset('storage/'.$raw) }}" style="height:60px;border-radius:8px;margin-bottom:6px">@endif<input id="f-{{ $name }}" type="file" name="{{ $name }}" accept="image/*">@break
@case('datetime')<input id="f-{{ $name }}" type="datetime-local" name="{{ $name }}" value="{{ $val ? \Carbon\Carbon::parse($val)->format('Y-m-d\TH:i') : '' }}">@break
@default<input id="f-{{ $name }}" name="{{ $name }}" value="{{ $val }}">
@endswitch
@error($name)<span class="err">{{ $message }}</span>@enderror
</div>
@endforeach
</div>
<button class="btn btn-p" style="margin-top:18px">Save</button></form>
@endsection
