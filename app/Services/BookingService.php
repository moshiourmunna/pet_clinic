<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Notifications\AppointmentBooked;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private SlotService $slots) {}

    public function book(array $data, ?int $userId = null): Appointment
    {
        $service = Service::findOrFail($data['service_id']);

        try {
            $appointment = DB::transaction(function () use ($data, $userId, $service) {
                $doctor = null;
                $slotKey = null;
                $fee = $service->price;

                if ($data['type'] === 'slot') {
                    // Row lock on the doctor serialises concurrent bookings for the same doctor (MySQL);
                    // the unique slot_key index is the final guard on every driver.
                    $doctor = Doctor::whereKey($data['doctor_id'])->lockForUpdate()->firstOrFail();
                    if (! $this->slots->isBookable($doctor, $data['date'], $data['time'])) {
                        throw $this->taken();
                    }
                    $slotKey = Appointment::slotKey($doctor->id, $data['date'], $data['time']);
                    $fee = max($doctor->fee, $service->price);
                }

                return Appointment::create([
                    'code' => 'PC'.now()->format('ymd').strtoupper(Str::random(5)),
                    'type' => $data['type'],
                    'user_id' => $userId,
                    'pet_id' => $data['pet_id'] ?? null,
                    'owner_name' => $data['owner_name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'] ?? null,
                    'pet_name' => $data['pet_name'],
                    'pet_type' => $data['pet_type'],
                    'pet_age_breed' => $data['pet_age_breed'] ?? null,
                    'service_id' => $service->id,
                    'doctor_id' => $doctor?->id,
                    'date' => $data['date'],
                    'time' => $data['type'] === 'slot' ? $data['time'] : null,
                    'visit_type' => $data['visit_type'] ?? 'clinic',
                    'symptoms' => $data['symptoms'] ?? null,
                    'status' => $data['type'] === 'slot' ? 'confirmed' : 'requested',
                    'payment_method' => $data['payment_method'] ?? 'clinic',
                    'payment_status' => 'unpaid',
                    'fee' => $fee,
                    'slot_key' => $slotKey,
                ]);
            });
        } catch (QueryException $e) {
            if (str_contains(strtolower($e->getMessage()), 'unique')) {
                throw $this->taken();
            }
            throw $e;
        }

        $this->notify($appointment);

        return $appointment;
    }

    public function reschedule(Appointment $a, string $date, ?string $time): Appointment
    {
        try {
            DB::transaction(function () use ($a, $date, $time) {
                if ($a->type === 'slot' && $a->doctor_id) {
                    $doctor = Doctor::whereKey($a->doctor_id)->lockForUpdate()->firstOrFail();
                    if (! $time || ! $this->slots->isBookable($doctor, $date, $time, $a->id)) {
                        throw $this->taken();
                    }
                    $a->update(['date' => $date, 'time' => $time, 'slot_key' => Appointment::slotKey($doctor->id, $date, $time)]);
                } else {
                    $a->update(['date' => $date, 'status' => 'requested']);
                }
            });
        } catch (QueryException $e) {
            throw $this->taken();
        }

        return $a->refresh();
    }

    private function taken(): ValidationException
    {
        return ValidationException::withMessages(['time' => __('This time slot has just been booked. Please choose another.')]);
    }

    private function notify(Appointment $a): void
    {
        try {
            $route = Notification::route('sms', $a->phone);
            if ($a->email) {
                $route->route('mail', $a->email);
            }
            $route->notify(new AppointmentBooked($a));
            // Clinic staff copy
            Notification::route('mail', \App\Models\Setting::get('email'))->notify(new AppointmentBooked($a, true));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
