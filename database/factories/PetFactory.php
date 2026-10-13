<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    public function definition(): array
    {
        return ['user_id' => User::factory(), 'name' => fake()->firstName(), 'species' => 'Dog', 'breed' => 'Mixed', 'age' => '2 yrs'];
    }
}
