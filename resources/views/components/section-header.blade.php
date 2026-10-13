@props(['tag', 'title', 'text' => null])
<div class="sh"><span class="tag">{{ $tag }}</span><h2>{!! $title !!}</h2>@if($text)<p>{{ $text }}</p>@endif</div>
