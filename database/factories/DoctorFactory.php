<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Specialty;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->optional()->phoneNumber(),
            'uin' => fake()->unique()->numerify('##########'),
            'is_gp' => fake()->boolean(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Attach specialties to the doctor.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($doctor) {
            $specialties = Specialty::inRandomOrder()->take(2)->pluck('id');
            $doctor->specialties()->attach($specialties);
        });
    }
}
