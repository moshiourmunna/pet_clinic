@extends('dashboard.layout')
@section('title', __('Payments'))
@section('dash')
<div class="card" style="overflow-x:auto"><h3 style="margin-bottom:12px">{{ __('Payments') }}</h3><table><tr><th>{{ __('Date') }}</th><th>{{ __('Booking Code') }}</th><th>{{ __('Service') }}</th><th>{{ __('Method') }}</th><th>{{ __('Transaction') }}</th><th>{{ __('Amount') }}</th><th>{{ __('Status') }}</th></tr>
@forelse($payments as $p)<tr><td>{{ $p->created_at->format('d M Y') }}</td><td>{{ $p->appointment->code }}</td><td>{{ $p->appointment->service->name }}</td><td>{{ $p->gateway === 'bkash' ? 'bKash' : 'SSLCommerz' }}</td><td>{{ $p->gateway_ref ?? $p->tran_id }}</td><td>৳{{ number_format($p->amount) }}</td><td><span class="pill {{ $p->status === 'paid' ? 'ok' : ($p->status === 'failed' ? 'bad' : 'wait') }}">{{ __(ucfirst($p->status)) }}</span></td></tr>@empty<tr><td colspan="7">{{ __('No records yet.') }}</td></tr>@endforelse
</table>{{ $payments->links() }}</div>
@endsection
