<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SickLeave;
use App\Services\SickLeaveService;

class SickLeaveController extends Controller
{
    public function __construct(private SickLeave $sickLeave, private SickLeaveService $sickLeaveService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sickLeaves = $this->sickLeave->all();

        return response()->json($sickLeaves, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'day_count' => 'required|integer|min:1',
        ]);

        try {
            $sickLeave = $this->sickLeaveService->createSickLeave($validated);

            return response()->json([
                'message' => 'Sick leave created successfully.',
                'sick_leave' => $sickLeave,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Sick leave creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while creating the sick leave.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $this->validateResourceId($id, 'Sick Leave');

        $sickLeave = $this->sickLeaveService->getSickLeaveById($id);

        if (!$sickLeave) {
            return response()->json(['message' => 'Sick leave not found'], 404);
        }

        return response()->json($sickLeave, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $this->validateResourceId($id, 'Sick Leave');

        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'day_count' => 'nullable|integer|min:1',
        ]);

        try {
            $sickLeave = $this->sickLeaveService->updateSickLeave($id, $validated);

            return response()->json([
                'message' => 'Sick leave updated successfully.',
                'sick_leave' => $sickLeave,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Sick leave update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while updating the sick leave.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = $this->validateResourceId($id, 'Sick Leave');

        $sickLeave = $this->sickLeaveService->getSickLeaveById($id);

        if (!$sickLeave) {
            return response()->json(['message' => 'Sick leave not found'], 404);
        }

        try {
            $sickLeave->delete();
            return response()->json(['message' => 'Sick leave deleted successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error('Sick leave deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while deleting the sick leave.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
