<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Visit;
use App\Models\Diagnosis;
use App\Models\SickLeave;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();

        User::factory()->create([
            'email' => 'patient@example.com',
            'role' => 'patient',
        ]);

        User::factory()->create([
            'email' => 'doctor@example.com',
            'role' => 'doctor',
        ]);

        Patient::factory()->create(['user_id' => 1]);

        Specialty::factory()->count(10)->create();
        Doctor::factory()->create(['user_id' => 2]);

        // Uncomment the following lines to seed sick leaves
        SickLeave::factory(10)->create();

        // Uncomment the following lines to seed visits and diagnoses
        Diagnosis::factory(20)->create();
        Visit::factory(50)->create();

    }
}
