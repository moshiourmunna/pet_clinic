<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class SlotService
{
    /**
     * Slots for a doctor on a date: generated from the weekly schedule, minus active bookings and past times.
     *
     * @return array<int, array{time: string, label: string, available: bool}>
     */
    public function forDate(Doctor $doctor, string $date, ?int $ignoreAppointmentId = null): array
    {
        $day = Carbon::parse($date)->startOfDay();
        if ($day->lt(today())) {
            return [];
        }

        $booked = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('date', $day->toDateString())
            ->whereNotNull('time')
            ->whereIn('status', ['requested', 'confirmed'])
            ->when($ignoreAppointmentId, fn ($q) => $q->where('id', '!=', $ignoreAppointmentId))
            ->pluck('time')
            ->map(fn ($t) => substr($t, 0, 5))
            ->all();

        $cutoff = now()->addMinutes(30);
        $slots = [];
        foreach ($doctor->schedules()->where('day_of_week', $day->dayOfWeek)->get() as $s) {
            $t = $day->copy()->setTimeFromTimeString($s->start_time);
            $end = $day->copy()->setTimeFromTimeString($s->end_time);
            while ($t->copy()->addMinutes($s->slot_minutes)->lte($end)) {
                $hm = $t->format('H:i');
                $slots[$hm] = [
                    'time' => $hm,
                    'label' => $t->format('g:i A'),
                    'available' => ! in_array($hm, $booked, true) && $t->gt($cutoff),
                ];
                $t->addMinutes($s->slot_minutes);
            }
        }
        ksort($slots);

        return array_values($slots);
    }

    public function isBookable(Doctor $doctor, string $date, string $time, ?int $ignoreAppointmentId = null): bool
    {
        foreach ($this->forDate($doctor, $date, $ignoreAppointmentId) as $slot) {
            if ($slot['time'] === substr($time, 0, 5)) {
                return $slot['available'];
            }
        }

        return false;
    }
}
