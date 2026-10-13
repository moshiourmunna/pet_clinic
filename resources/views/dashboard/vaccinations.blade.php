@extends('dashboard.layout')
@section('title', __('Vaccinations'))
@section('dash')
<div class="card" style="overflow-x:auto"><h3 style="margin-bottom:12px">{{ __('Vaccinations') }}</h3><table><tr><th>{{ __('Pet') }}</th><th>{{ __('Vaccine') }}</th><th>{{ __('Given On') }}</th><th>{{ __('Next Due') }}</th><th>{{ __('Status') }}</th></tr>
@forelse($vaccinations as $v)<tr><td>{{ $v->pet->name }}</td><td>{{ $v->vaccine }}</td><td>{{ $v->given_on?->format('d M Y') ?? '—' }}</td><td>{{ $v->due_on?->format('d M Y') ?? '—' }}</td><td>@if($v->due_on && $v->due_on->lte(today()->addDays(30)))<span class="pill wait">{{ __('Vaccine Due') }}</span>@else<span class="pill ok">OK</span>@endif</td></tr>@empty<tr><td colspan="5">{{ __('No records yet.') }}</td></tr>@endforelse
</table>{{ $vaccinations->links() }}</div>
@endsection
