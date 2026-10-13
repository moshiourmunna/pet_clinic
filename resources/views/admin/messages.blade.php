@extends('admin.layout')
@section('title', 'Messages')
@section('admin')
<h2 style="margin-bottom:16px">Contact messages</h2>
<div class="card" style="overflow-x:auto"><table><tr><th>Date</th><th>From</th><th>Message</th><th>Actions</th></tr>
@forelse($items as $m)<tr style="{{ $m->read_at ? 'opacity:.65' : 'font-weight:500' }}"><td>{{ $m->created_at->format('d M Y H:i') }}</td><td>{{ $m->name }}<br><small>{{ $m->phone }} {{ $m->email }}</small></td><td>{{ $m->message }}</td><td style="white-space:nowrap"><form class="inline" method="post" action="{{ route('admin.messages.update', $m) }}">@csrf @method('PATCH')<button class="btn btn-o btn-sm">{{ $m->read_at ? 'Mark unread' : 'Mark read' }}</button></form> <form class="inline" method="post" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-d btn-sm">Delete</button></form></td></tr>@empty<tr><td colspan="4">No messages.</td></tr>@endforelse
</table>{{ $items->links() }}</div>
@endsection
