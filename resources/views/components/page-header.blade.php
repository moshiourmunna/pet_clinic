@props(['title'])
<section class="pagehead"><div class="wrap"><h1>{{ $title }}</h1><div class="crumb"><a href="{{ route('home') }}"><span>{{ __('Home') }}</span></a> / {{ $slot->isEmpty() ? '' : $slot }}<span>{{ $title }}</span></div></div></section>
