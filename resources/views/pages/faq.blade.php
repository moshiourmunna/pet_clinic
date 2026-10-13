@extends('layouts.app')
@section('title', __('Frequently Asked Questions'))
@section('content')
<x-page-header :title="__('Frequently Asked Questions')"/><section class="sec"><div class="wrap" style="max-width:860px"><x-faq-list :faqs="$faqs"/></div></section>@include('partials.cta')
@endsection
