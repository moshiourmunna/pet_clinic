@extends('admin.layout')
@section('title', $cfg['label'])
@section('admin')
<div class="admin-top"><h2>{{ $cfg['label'] }}</h2><div style="display:flex;gap:8px"><form method="get"><input name="q" value="{{ request('q') }}" placeholder="Search…" style="padding:9px 12px"></form><a class="btn btn-p" href="{{ route('admin.content.create', $resource) }}"><i class="fa fa-plus"></i>Add</a></div></div>
<div class="card" style="overflow-x:auto"><table><tr>@foreach($cfg['columns'] as $col => $label)<th>{{ $label }}</th>@endforeach<th style="width:200px">Actions</th></tr>
@forelse($items as $item)<tr>
@foreach($cfg['columns'] as $col => $label)<td>
@php($v = $item->{$col})
@if($col === 'image')<img src="{{ $item->url }}" style="width:70px;height:50px;object-fit:cover;border-radius:8px">
@elseif(is_bool($v) || in_array($col, ['is_active', 'is_featured']))<span class="pill {{ $v ? 'ok' : 'wait' }}">{{ $v ? 'Yes' : 'No' }}</span>
@elseif($v instanceof \Carbon\CarbonInterface){{ $v->format('d M Y') }}
@else{{ \Illuminate\Support\Str::limit((string) $v, 60) }}@endif
</td>@endforeach
<td style="white-space:nowrap"><a class="btn btn-o btn-sm" href="{{ route('admin.content.edit', [$resource, $item->id]) }}">Edit</a>
@if($resource === 'doctors')<a class="btn btn-o btn-sm" href="{{ route('admin.doctors.schedules', $item) }}">Schedule</a>@endif
<form class="inline" method="post" action="{{ route('admin.content.destroy', [$resource, $item->id]) }}" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="btn btn-d btn-sm">Delete</button></form></td></tr>
@empty<tr><td colspan="9">Nothing here yet.</td></tr>@endforelse
</table>{{ $items->links() }}</div>
@endsection
