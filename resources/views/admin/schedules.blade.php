@extends('admin.layout')
@section('title', 'Schedule')
@section('admin')
<form class="card" method="post" action="{{ route('admin.doctors.schedules', $doctor) }}" id="sched">@csrf @method('PUT')
<div class="admin-top"><h2>Weekly schedule — {{ $doctor->name }}</h2><a class="btn btn-o btn-sm" href="{{ route('admin.content.index', 'doctors') }}">← Doctors</a></div>
<table><thead><tr><th>Day</th><th>Start</th><th>End</th><th>Slot (min)</th><th></th></tr></thead><tbody id="rows">
@foreach($doctor->schedules as $i => $s)
<tr><td><select name="rows[{{ $i }}][day_of_week]">@foreach(\App\Models\DoctorSchedule::DAYS as $k => $d)<option value="{{ $k }}" @selected($s->day_of_week == $k)>{{ $d }}</option>@endforeach</select></td><td><input type="time" name="rows[{{ $i }}][start_time]" value="{{ substr($s->start_time, 0, 5) }}"></td><td><input type="time" name="rows[{{ $i }}][end_time]" value="{{ substr($s->end_time, 0, 5) }}"></td><td><input type="number" name="rows[{{ $i }}][slot_minutes]" value="{{ $s->slot_minutes }}" min="5" max="240"></td><td><button type="button" class="btn btn-d btn-sm" onclick="this.closest('tr').remove()">×</button></td></tr>
@endforeach
</tbody></table>
<template id="tpl"><tr><td><select name="rows[__][day_of_week]">@foreach(\App\Models\DoctorSchedule::DAYS as $k => $d)<option value="{{ $k }}">{{ $d }}</option>@endforeach</select></td><td><input type="time" name="rows[__][start_time]" value="10:00"></td><td><input type="time" name="rows[__][end_time]" value="14:00"></td><td><input type="number" name="rows[__][slot_minutes]" value="30" min="5" max="240"></td><td><button type="button" class="btn btn-d btn-sm" onclick="this.closest('tr').remove()">×</button></td></tr></template>
<div style="display:flex;gap:10px;margin-top:14px"><button type="button" class="btn btn-o" onclick="const t=document.getElementById('tpl').innerHTML.replaceAll('__',Date.now());document.getElementById('rows').insertAdjacentHTML('beforeend',t)">+ Add session</button><button class="btn btn-p">Save schedule</button></div>
</form>
@endsection
