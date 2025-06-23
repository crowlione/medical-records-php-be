<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientService
{
    public function __construct(private Patient $patient)
    {

    }

    public function getPatientById($patientId): ?Patient
    {
        return $this->patient->find($patientId);
    }

    public function updatePatient($patientId, array $data): Patient
    {
        $patient = $this->getPatientById($patientId);
        if (!$patient) {
            throw new \Exception("Patient not found");
        }

        // Update the patient's information
        $patient->first_name = $data['first_name'] ?? $patient->first_name;
        $patient->last_name = $data['last_name'] ?? $patient->last_name;
        $patient->phone = $data['phone'] ?? $patient->phone;
        $patient->egn = $data['egn'] ?? $patient->egn;
        $patient->has_insurance = $data['has_insurance'] ?? $patient->has_insurance;
        $patient->gp_id = $data['gp_id'] ?? $patient->gp_id;

        // Save the updated patient
        $patient->save();

        return $patient;
    }

    public function getPatientsByDiagnosis($diagnosisId): Collection
    {
        return $this->patient
            ->select('patients.*')
            ->join('visits', 'patients.id', '=', 'visits.patient_id')
            ->join('diagnosis_visit', 'visits.id', '=', 'diagnosis_visit.visit_id')
            ->where('diagnosis_visit.diagnosis_id', $diagnosisId)
            ->get();
    }

    public function getPatientsByGp($gpId): Collection
    {
        return $this->patient
            ->select('patients.*')
            ->where('gp_id', $gpId)
            ->get();
    }

    public function countPatientsByGps()
    {
        return $this->patient
            ->selectRaw('gp_id, COUNT(*) as patient_count')
            ->groupBy('gp_id')
            ->get();
    }
}
