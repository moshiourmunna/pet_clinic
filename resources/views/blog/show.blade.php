@extends('layouts.app')
@section('title', $post->title)
@section('description', $post->excerpt)
@section('og_image', $post->image_url)
@section('content')
<x-page-header :title="$post->title"><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a> / </x-page-header>
<section class="sec"><div class="wrap" style="max-width:860px"><article class="card prose" style="padding:0;overflow:hidden"><img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;aspect-ratio:16/8;object-fit:cover"><div style="padding:30px"><div style="font-size:13px;color:var(--p);font-weight:600;margin-bottom:10px">{{ $post->published_at->format('d M Y') }} · {{ $post->category }}</div>{!! $post->body !!}</div></article></div></section>
@if($related->count())<section class="sec alt"><div class="wrap"><x-section-header :tag="__('Blog')" :title="__('Pet Care Tips & News')"/><div class="grid g3">@foreach($related as $p)<x-post-card :post="$p"/>@endforeach</div></div></section>@endif
@endsection
