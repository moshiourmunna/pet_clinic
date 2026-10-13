<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $n = fake()->unique()->words(2, true);
        return ['slug' => Str::slug($n), 'name_en' => ucfirst($n), 'name_bn' => null, 'icon' => 'fa-paw', 'price' => 500, 'is_active' => true];
    }
}
