<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(private User $user, private UserService $userService)
    {
    }

    /**
     * Handle patient registration and return an API token.
     */
    public function registerPatient(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'egn' => 'required|string|unique:patients,egn',
            'has_paid_insurance' => 'nullable|boolean',
            'gp_id' => 'nullable|exists:doctors,id',
        ]);

        $user = $this->userService->createPatientUser($validated);
        $user->load('patient');

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    /**
     * Handle doctor registration and return an API token.
     */
    public function registerDoctor(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'uin' => 'nullable|string|max:20',
            'is_gp' => 'nullable|boolean',
        ]);

        $user = $this->userService->createDoctorUser($validated);
        $user->load('doctor');

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    /**
     * Handle user login and return an API token.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->user->where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Load the related model based on user role
        if ($user->role === 'patient') {
            $user->load('patient');
        } elseif ($user->role === 'doctor') {
            $user->load('doctor');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
