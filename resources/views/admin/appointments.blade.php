@extends('admin.layout')
@section('title', 'Appointments')
@section('admin')
<div class="admin-top"><h2>Appointments</h2></div>
<form class="card" method="get" style="display:flex;gap:10px;flex-wrap:wrap;padding:14px;margin-bottom:16px">
<input name="q" value="{{ request('q') }}" placeholder="Code, phone or name" style="flex:2;min-width:180px">
<select name="status" style="flex:1;min-width:130px"><option value="">All statuses</option>@foreach(\App\Models\Appointment::STATUSES as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>@endforeach</select>
<select name="doctor" style="flex:1;min-width:150px"><option value="">All doctors</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(request('doctor') == $d->id)>{{ $d->name }}</option>@endforeach</select>
<input type="date" name="date" value="{{ request('date') }}" style="flex:1;min-width:140px"><button class="btn btn-p btn-sm">Filter</button></form>
<div class="card" style="overflow-x:auto"><table><tr><th>Code</th><th>Type</th><th>Owner</th><th>Pet</th><th>Service / Doctor</th><th>Date</th><th>Pay</th><th>Status / Action</th></tr>
@forelse($items as $a)
<tr><td>{{ $a->code }}</td><td>{{ $a->type }}</td><td>{{ $a->owner_name }}<br><small><a href="tel:{{ $a->phone }}">{{ $a->phone }}</a></small></td><td>{{ $a->pet_name }}<br><small>{{ $a->pet_type }} {{ $a->pet_age_breed }}</small></td><td>{{ $a->service->name }}<br><small>{{ $a->doctor?->name ?? 'Unassigned' }} · {{ \App\Models\Appointment::VISIT_TYPES[$a->visit_type] ?? '' }}</small></td><td>{{ $a->date->format('d M Y') }}<br><small>{{ $a->time_label }}</small></td><td><span class="pill {{ $a->payment_status === 'paid' ? 'paid' : 'wait' }}">{{ $a->payment_method }} · {{ $a->payment_status }}</span></td>
<td><form method="post" action="{{ route('admin.appointments.update', $a) }}" style="display:flex;gap:6px;flex-wrap:wrap;min-width:260px">@csrf @method('PATCH')
<select name="status" style="padding:7px;width:auto">@foreach(\App\Models\Appointment::STATUSES as $s)<option value="{{ $s }}" @selected($a->status === $s)>{{ ucfirst($s) }}</option>@endforeach</select>
<select name="payment_status" style="padding:7px;width:auto"><option value="unpaid" @selected($a->payment_status !== 'paid')>Unpaid</option><option value="paid" @selected($a->payment_status === 'paid')>Paid</option></select>
@if($a->type === 'request')<select name="doctor_id" style="padding:7px;width:auto"><option value="">Assign doctor</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected($a->doctor_id === $d->id)>{{ $d->name }}</option>@endforeach</select>@endif
<button class="btn btn-p btn-sm">Save</button></form>@if($a->symptoms)<small title="{{ $a->symptoms }}"><i class="fa fa-notes-medical"></i> {{ \Illuminate\Support\Str::limit($a->symptoms, 40) }}</small>@endif</td></tr>
@empty<tr><td colspan="8">No appointments.</td></tr>@endforelse
</table>{{ $items->links() }}</div>
@endsection
