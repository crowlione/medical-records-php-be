<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function __construct(private Diagnosis $diagnosis, private DiagnosisService $diagnosisService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnoses = $this->diagnosis->all();

        return response()->json($diagnoses, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $diagnosis = $this->diagnosisService->createDiagnosis($validated);

            return response()->json([
                'message' => 'Diagnosis created successfully.',
                'diagnosis' => $diagnosis,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Diagnosis creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while creating the diagnosis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $this->validateResourceId($id, 'Diagnosis');

        $diagnosis = $this->diagnosisService->getDiagnosisById($id);

        if (!$diagnosis) {
            return response()->json(['message' => 'Diagnosis not found'], 404);
        }

        return response()->json($diagnosis, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $this->validateResourceId($id, 'Diagnosis');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $diagnosis = $this->diagnosisService->updateDiagnosis($id, $validated);

            return response()->json([
                'message' => 'Diagnosis updated successfully.',
                'diagnosis' => $diagnosis,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Diagnosis update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while updating the diagnosis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = $this->validateResourceId($id, 'Diagnosis');

        $diagnosis = $this->diagnosisService->getDiagnosisById($id);

        if (!$diagnosis) {
            return response()->json(['message' => 'Diagnosis not found'], 404);
        }

        try {
            $diagnosis->delete();

            return response()->json(['message' => 'Diagnosis deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Diagnosis deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while deleting the diagnosis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
