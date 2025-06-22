<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Services\VisitService;
use App\Http\Resources\VisitResource;

class VisitController extends Controller
{
    public function __construct(private Visit $visit, private VisitService $visitService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visits = $this->visit->all();

        return response()->json($visits, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'visit_date' => 'required|date',
            'treatment' => 'nullable|string|max:1000',
            'sick_leave_id' => 'nullable|integer|exists:sick_leaves,id',
        ]);

        try {
            $visit = $this->visitService->createVisit($validated);

            $visit->load('patient', 'doctor', 'sickLeave');

            return response()->json([
                'message' => 'Visit created successfully.',
                'visit' => $visit,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Visit creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while creating the visit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $this->validateResourceId($id, 'Visit');

        $visit = $this->visit->with(['patient', 'doctor', 'sickLeave', 'diagnoses'])->find($id);

        if (!$visit) {
            return response()->json(['message' => 'Visit not found'], 404);
        }

        return new VisitResource($visit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $this->validateResourceId($id, 'Visit');

        $validated = $request->validate([
            'patient_id' => 'sometimes|integer|exists:patients,id',
            'doctor_id' => 'sometimes|integer|exists:doctors,id',
            'visit_date' => 'sometimes|date',
            'treatment' => 'nullable|string|max:1000',
            'sick_leave_id' => 'nullable|integer|exists:sick_leaves,id',
            'diagnosis_ids' => 'sometimes|array',
            'diagnosis_ids.*' => 'exists:diagnoses,id',
        ]);

        try {
            $visit = $this->visitService->updateVisit($id, $validated);

            return (new VisitResource($visit))
                ->additional(['message' => 'Visit updated successfully.']);
        } catch (\Exception $e) {
            \Log::error('Visit update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while updating the visit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = $this->validateResourceId($id, 'Visit');

        $visit = $this->visitService->getVisitById($id);

        if (!$visit) {
            return response()->json(['message' => 'Visit not found'], 404);
        }

        try {
            $visit->delete();
            return response()->json(['message' => 'Visit deleted successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error('Visit deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while deleting the visit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
