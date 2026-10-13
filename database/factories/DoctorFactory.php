<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DoctorFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Dr. '.fake()->name();
        return [
            'slug' => Str::slug($name).'-'.Str::random(4), 'name_en' => $name, 'name_bn' => null,
            'title_en' => 'General Practitioner', 'speciality' => 'medicine', 'experience' => fake()->numberBetween(2, 20),
            'fee' => 800, 'rating' => 4.8, 'photo' => 'assets/img3.jpg', 'is_active' => true,
        ];
    }

    /** Works every day 10:00–12:00, 30-min slots. */
    public function everyDay(): static
    {
        return $this->afterCreating(function ($d) {
            foreach (range(0, 6) as $day) {
                $d->schedules()->create(['day_of_week' => $day, 'start_time' => '10:00', 'end_time' => '12:00', 'slot_minutes' => 30]);
            }
        });
    }
}
