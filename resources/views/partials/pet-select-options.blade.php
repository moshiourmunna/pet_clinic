@foreach(\App\Models\Pet::SPECIES as $sp)<option value="{{ $sp }}" @selected(($selectedPet ?? null) === $sp)>{{ __($sp) }}</option>@endforeach
