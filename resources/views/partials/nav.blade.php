@php($menu = ['home' => 'Home', 'about' => 'About', 'services' => 'Services', 'doctors.index' => 'Doctors', 'pricing' => 'Pricing', 'gallery' => 'Gallery', 'blog.index' => 'Blog', 'faq' => 'FAQ', 'contact' => 'Contact'])
<header class="nav"><div class="wrap"><a class="logo" href="{{ route('home') }}"><img src="{{ asset('assets/logo.svg') }}" alt="{{ $settings['clinic_name'] }}"></a>
<ul class="menu">
@foreach($menu as $r => $label)
<li><a href="{{ route($r) }}" @class(['on' => request()->routeIs($r, str_contains($r, '.') ? \Illuminate\Support\Str::before($r, '.').'.*' : $r)])>{{ __($label) }}</a></li>
@endforeach
</ul>
<div class="nav-r"><div class="lang"><a href="{{ route('locale.switch', 'en') }}" @class(['on' => app()->getLocale() === 'en']) hreflang="en">EN</a><a href="{{ route('locale.switch', 'bn') }}" @class(['on' => app()->getLocale() === 'bn']) hreflang="bn">বাং</a></div>
@auth
<a class="btn btn-o" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" style="padding:9px 14px" title="{{ __('Dashboard') }}"><i class="fa fa-user-check"></i></a>
@else
<a class="btn btn-o" href="{{ route('login') }}" style="padding:9px 14px" title="Account"><i class="fa fa-user"></i></a>
@endauth
<a class="btn btn-p" href="{{ route('appointment.create') }}"><i class="fa fa-calendar-check"></i><span>{{ __('Book Now') }}</span></a><button class="ham" aria-label="Menu"><i class="fa fa-bars"></i></button></div></div></header>
