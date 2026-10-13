@extends('admin.layout')
@section('title', 'Admin Dashboard')
@section('admin')
<div class="admin-top"><h2>Dashboard</h2><a class="btn btn-p" href="{{ route('admin.appointments.index', ['status' => 'requested']) }}"><i class="fa fa-bell"></i>{{ $stats['pending'] }} pending requests</a></div>
<div class="grid g3" style="margin-bottom:24px">
<div class="card stat"><i class="fa fa-calendar-day"></i><div><b>{{ $stats['today'] }}</b><small>Today's appointments</small></div></div>
<div class="card stat"><i class="fa fa-hourglass-half" style="background:var(--al);color:var(--a)"></i><div><b>{{ $stats['pending'] }}</b><small>Pending requests</small></div></div>
<div class="card stat"><i class="fa fa-sack-dollar"></i><div><b>৳{{ number_format($stats['revenue']) }}</b><small>Online revenue (this month)</small></div></div>
<div class="card stat"><i class="fa fa-users"></i><div><b>{{ $stats['customers'] }}</b><small>Customers</small></div></div>
<div class="card stat"><i class="fa fa-user-doctor"></i><div><b>{{ $stats['doctors'] }}</b><small>Active doctors</small></div></div>
<div class="card stat"><i class="fa fa-envelope" style="background:var(--al);color:var(--a)"></i><div><b>{{ $stats['messages'] }}</b><small>Unread messages</small></div></div>
</div>
<div class="card" style="overflow-x:auto"><div class="admin-top"><h3>Latest appointments</h3><a class="btn btn-o btn-sm" href="{{ route('admin.appointments.index') }}">View all</a></div>
<table><tr><th>Code</th><th>Owner</th><th>Pet</th><th>Service</th><th>Doctor</th><th>Date</th><th>Status</th><th>Payment</th></tr>
@foreach($latest as $a)<tr><td>{{ $a->code }}</td><td>{{ $a->owner_name }}<br><small>{{ $a->phone }}</small></td><td>{{ $a->pet_name }}</td><td>{{ $a->service->name }}</td><td>{{ $a->doctor?->name ?? '—' }}</td><td>{{ $a->date->format('d M') }} {{ $a->time_label }}</td><td><span class="pill {{ $a->statusClass() }}">{{ ucfirst($a->status) }}</span></td><td><span class="pill {{ $a->payment_status === 'paid' ? 'paid' : 'wait' }}">{{ $a->payment_status }}</span></td></tr>@endforeach
</table></div>
@endsection
