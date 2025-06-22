<?php

namespace App\Services;

use App\Models\Visit;

class VisitService
{
    public function __construct(private Visit $visit)
    {
    }

    public function createVisit(array $data): Visit
    {
        $visit = $this->visit->create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'visit_date' => $data['visit_date'],
            'treatment' => $data['treatment'] ?? null,
            'sick_leave_id' => $data['sick_leave_id'] ?? null,
        ]);

        return $visit;
    }

    public function getVisitById($visitId): ?Visit
    {
        return $this->visit->find($visitId);
    }

    public function updateVisit($visitId, array $data): Visit
    {
        $visit = $this->getVisitById($visitId);
        if (!$visit) {
            throw new \Exception("Visit not found");
        }

        $visit->update(($data));

        // Sync diagnoses if provided
        if (array_key_exists('diagnosis_ids', $data)) {
            $visit->diagnoses()->sync($data['diagnosis_ids']);
        }

        $visit->load('patient', 'doctor', 'sickLeave', 'diagnoses');

        return $visit;
    }
}
