<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
@php
$ldJson = json_encode(['@'.'context' => 'https://schema.org', '@'.'type' => 'VeterinaryCare', 'name' => $settings['clinic_name'], 'telephone' => $settings['phone'], 'email' => $settings['email'], 'address' => $settings['address_en'], 'url' => url('/')], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
$appJs = json_encode(['csrf' => csrf_token(), 'locale' => app()->getLocale(), 't' => ['loading' => __('Loading...'), 'noSlots' => __('No slots available on this date.'), 'pickDate' => __('Select a date to see available slots.'), 'noDoctors' => __('No doctors found.'), 'atClinic' => __('At clinic')]], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
@endphp
<meta name="csrf-token" content="{{ csrf_token() }}">
@php($pageTitle = trim($__env->yieldContent('title')) ?: __('Home'))
@php($metaDesc = trim($__env->yieldContent('description')) ?: __('Experienced vets, modern equipment and 24/7 emergency support. Book an appointment in just a few clicks, with or without online payment.'))
<title>{{ $pageTitle }} | {{ $settings['clinic_name'] }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDesc), 160) }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website"><meta property="og:site_name" content="{{ $settings['clinic_name'] }}">
<meta property="og:title" content="{{ $pageTitle }} | {{ $settings['clinic_name'] }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDesc), 160) }}">
<meta property="og:image" content="@yield('og_image', asset('assets/img8.jpg'))">
<meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">
<meta name="theme-color" content="#0F9D8F">
<link rel="icon" href="{{ asset('assets/icon.svg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/style.css') }}"><link rel="stylesheet" href="{{ asset('assets/extra.css') }}">
<script type="application/ld+json">{!! $ldJson !!}</script>
@stack('head')
</head>
<body class="{{ app()->getLocale() === 'bn' ? 'bn' : '' }}">
@include('partials.topbar')
@include('partials.nav')
<main>
@yield('content')
</main>
@include('partials.footer')
@include('partials.fab')
@include('partials.mbar')
<div class="toast" id="toast"></div>
<script>window.APP={!! $appJs !!};</script>
<script src="{{ asset('assets/app.js') }}"></script>
@stack('scripts')
</body>
</html>
