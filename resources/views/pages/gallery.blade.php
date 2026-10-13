@extends('layouts.app')
@section('title', __('Gallery'))
@section('content')
<x-page-header :title="__('Gallery')"/><section class="sec"><div class="wrap"><div class="gal">@foreach($images as $img)<img src="{{ $img->url }}" alt="{{ $img->caption }}" loading="lazy">@endforeach</div>{{ $images->links() }}</div></section>@include('partials.cta')
@endsection
