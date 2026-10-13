@forelse($doctors as $doctor)<x-doctor-card :doctor="$doctor"/>@empty<p style="grid-column:1/-1;text-align:center">{{ __('No doctors found.') }}</p>@endforelse
