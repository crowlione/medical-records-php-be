<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Services\PatientService;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PatientController extends Controller
{
    use AuthorizesRequests;
    public function __construct(private Patient $patient, private PatientService $patientService, private UserService $userService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = $this->patient->all();

        return response()->json($patients, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'egn' => 'required|string|unique:patients,egn',
            'has_insurance' => 'nullable|boolean',
            'gp_id' => 'nullable|exists:doctors,id',
        ]);

        try {
            $user = $this->userService->createPatientUser($validated);

            $user->load('patient');

            return response()->json([
                'message' => 'Patient created successfully.',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Patient creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while creating the patient.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $this->validateResourceId($id, 'Patient');

        $patient = $this->patientService->getPatientById($id);

        $this->authorize('view', $patient);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($patient, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $this->validateResourceId($id, 'Patient');

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'egn' => 'sometimes|required|string|unique:patients,egn,' . $id,
            'has_insurance' => 'nullable|boolean',
            'gp_id' => 'nullable|exists:doctors,id',
        ]);

        try {
            $patient = $this->patientService->updatePatient($id, $validated);

            return response()->json([
                'message' => 'Patient updated successfully.',
                'patient' => $patient,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Patient update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while updating the patient.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = $this->validateResourceId($id, 'Patient');

        $patient = $this->patientService->getPatientById($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        try {
            $patient->delete();
            return response()->json(['message' => 'Patient deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Patient deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while deleting the patient.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all patients with a specific diagnosis.
     */
    public function diagnosis(string $diagnosisId)
    {
        $diagnosisId = $this->validateResourceId($diagnosisId, 'Diagnosis');

        $patients = $this->patientService->getPatientsByDiagnosis($diagnosisId);
        return response()->json($patients, 200);
    }

    /**
     * Get all patients for a specific GP.
     */
    public function gp(string $gpId)
    {
        $gpId = $this->validateResourceId($gpId, 'Doctor');

        $patients = $this->patientService->getPatientsByGp($gpId);
        return response()->json($patients, 200);
    }

    /**
     * Count all patients by GP.
     */
    public function countByGps()
    {
        $counts = $this->patientService->countPatientsByGps();
        return response()->json($counts, 200);
    }
}
