<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user(); }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'species' => ['required', Rule::in(\App\Models\Pet::SPECIES)],
            'breed' => ['nullable', 'string', 'max:60'],
            'age' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
