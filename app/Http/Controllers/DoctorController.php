<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    public function __construct(private Doctor $doctor, private DoctorService $doctorService, private UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = $this->doctor->all();

        return response()->json($doctors, 200);
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
            'uin' => 'nullable|string|max:20',
            'is_gp' => 'nullable|boolean',
        ]);

        try {
            $user = $this->userService->createDoctorUser($validated);

            $user->load('doctor');

            return response()->json([
                'message' => 'Doctor created successfully.',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Doctor creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while creating the doctor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $this->validateResourceId($id, 'Doctor');

        $doctor = $this->doctorService->getDoctorById($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $this->validateResourceId($id, 'Doctor');

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'uin' => 'nullable|string|max:20',
            'is_gp' => 'nullable|boolean',
        ]);

        try {
            $doctor = $this->doctorService->updateDoctor($id, $validated);

            return response()->json([
                'message' => 'Doctor updated successfully.',
                'doctor' => $doctor,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Doctor update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while updating the doctor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = $this->validateResourceId($id, 'Doctor');

        $doctor = $this->doctorService->getDoctorById($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        try {
            $doctor->delete();
            return response()->json(['message' => 'Doctor deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Doctor deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred while deleting the doctor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
