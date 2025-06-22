<?php

namespace App\Services;

use App\Models\Doctor;

class DoctorService
{

    public function __construct(private Doctor $doctor)
    {
    }

    public function getDoctorById($doctorId)
    {
        return $this->doctor->find($doctorId);
    }

    public function updateDoctor($doctorId, array $data): Doctor
    {
        $doctor = $this->getDoctorById($doctorId);
        if (!$doctor) {
            throw new \Exception("Doctor not found");
        }

        // Update the doctor's information
        $doctor->first_name = $data['first_name'] ?? $doctor->first_name;
        $doctor->last_name = $data['last_name'] ?? $doctor->last_name;
        $doctor->phone = $data['phone'] ?? $doctor->phone;
        $doctor->uin = $data['uin'] ?? $doctor->uin;
        $doctor->is_gp = $data['is_gp'] ?? $doctor->is_gp;

        // Save the updated doctor
        $doctor->save();

        return $doctor;
    }

    public function getDoctorsBySpecialty($specialtyId)
    {
        // Logic to fetch doctors by specialty
        // This is just a placeholder method
        return [];
    }
}
