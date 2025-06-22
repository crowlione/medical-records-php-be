<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Diagnosis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'visit_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'treatment' => fake()->sentence(),
        ];
    }

    /** 
     * Attach diagnoses to the visit.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($visit) {
            $diagnoses = Diagnosis::inRandomOrder()->take(2)->pluck('id');
            $visit->diagnoses()->attach($diagnoses);
        });
    }
}
