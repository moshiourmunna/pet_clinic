@extends('dashboard.layout')
@section('title', __('Medical Records'))
@section('dash')
<div class="card" style="overflow-x:auto"><h3 style="margin-bottom:12px">{{ __('Medical Records') }}</h3><table><tr><th>{{ __('Date') }}</th><th>{{ __('Pet') }}</th><th>{{ __('Doctor') }}</th><th>{{ __('Diagnosis') }}</th><th>{{ __('Treatment') }}</th></tr>
@forelse($records as $r)<tr><td>{{ $r->visit_date->format('d M Y') }}</td><td>{{ $r->pet->name }}</td><td>{{ $r->doctor?->name ?? '—' }}</td><td>{{ $r->diagnosis }}</td><td>{{ $r->treatment }}</td></tr>@empty<tr><td colspan="5">{{ __('No records yet.') }}</td></tr>@endforelse
</table>{{ $records->links() }}</div>
@endsection
