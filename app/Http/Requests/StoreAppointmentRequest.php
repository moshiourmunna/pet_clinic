<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge(['phone' => preg_replace('/[^\d+]/', '', (string) $this->phone)]);
    }

    public function rules(): array
    {
        $slot = $this->input('type') === 'slot';

        return [
            'type' => ['required', Rule::in(['slot', 'request'])],
            'owner_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^(\+?880|0)1[3-9]\d{8}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'pet_id' => ['nullable', Rule::exists('pets', 'id')->where('user_id', $this->user()?->id ?? 0)],
            'pet_name' => ['required', 'string', 'max:60'],
            'pet_type' => ['required', Rule::in(['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'])],
            'pet_age_breed' => ['nullable', 'string', 'max:100'],
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'doctor_id' => [Rule::requiredIf($slot), 'nullable', Rule::exists('doctors', 'id')->where('is_active', true)],
            'date' => ['required', 'date', 'after_or_equal:today', 'before:'.now()->addMonths(3)->toDateString()],
            'time' => [Rule::requiredIf($slot), 'nullable', 'date_format:H:i'],
            'visit_type' => ['required', Rule::in(array_keys(Appointment::VISIT_TYPES))],
            'symptoms' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(array_keys(Appointment::PAYMENT_METHODS))],
            'terms' => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return ['owner_name' => 'name', 'doctor_id' => 'doctor', 'service_id' => 'service', 'time' => 'time slot'];
    }
}
