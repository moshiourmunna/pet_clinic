@extends('layouts.app')
@section('title', __('Blog & Pet Tips'))
@section('description', __('Expert advice to keep your pet healthy and happy.'))
@section('content')
<x-page-header :title="__('Blog & Pet Tips')"/>
<section class="sec"><div class="wrap"><div class="grid g3">@foreach($posts as $post)<x-post-card :post="$post"/>@endforeach</div>{{ $posts->links() }}</div></section>
@endsection
