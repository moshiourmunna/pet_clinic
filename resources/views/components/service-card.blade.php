@props(['service'])
<div class="card svc"><div class="ic"><i class="fa {{ $service->icon }}"></i></div><h3>{{ $service->name }}</h3><p>{{ $service->description }}</p><a class="more" href="{{ route('appointment.create', ['service' => $service->slug]) }}"><span>{{ __('Book this') }}</span> <i class="fa fa-arrow-right"></i></a></div>
