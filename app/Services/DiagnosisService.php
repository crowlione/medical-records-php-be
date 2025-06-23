<?php

namespace App\Services;

use App\Models\Diagnosis;
use Illuminate\Database\Eloquent\Collection;

class DiagnosisService
{
    public function __construct(private Diagnosis $diagnosis)
    {
    }

    public function createDiagnosis(array $data): Diagnosis
    {
        $diagnosis = $this->diagnosis->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $diagnosis;
    }

    public function getDiagnosisById($diagnosisId): ?Diagnosis
    {
        return $this->diagnosis->find($diagnosisId);
    }

    public function updateDiagnosis($diagnosisId, array $data): Diagnosis
    {
        $diagnosis = $this->getDiagnosisById($diagnosisId);
        if (!$diagnosis) {
            throw new \Exception("Diagnosis not found");
        }

        $diagnosis->name = $data['name'] ?? $diagnosis->name;
        $diagnosis->description = $data['description'] ?? $diagnosis->description;

        $diagnosis->save();

        return $diagnosis;
    }

    /**
     * Get the top one most common diagnosis.
     */
    public function getMostCommonDiagnosis(): Collection
    {
        // Return all most common diagnoses
        return $this->diagnosis
            ->select('diagnoses.*')
            ->join('diagnosis_visit', 'diagnoses.id', '=', 'diagnosis_visit.diagnosis_id')
            ->groupBy('diagnoses.id')
            ->orderByRaw('COUNT(diagnosis_visit.visit_id) DESC')
            ->limit(1)
            ->get();
    }
}
