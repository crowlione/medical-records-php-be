<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Doctor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
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
            'egn' => fake()->unique()->numerify('##########'),
            'has_insurance' => fake()->boolean(),
            'user_id' => User::factory([
                'role' => 'patient',
            ])->create()->id,
        ];
    }
    /**
     * Attach GP to the patient.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($patient) {
            // Get a random GP doctor
            $gp = Doctor::where('is_gp', true)
                ->inRandomOrder()
                ->first()
                ->id;
            $patient->gp_id = $gp;
            $patient->save();
        });
    }
}
