<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

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

        Auth::login($user);
        return response()->json([
            'message' => 'Registered and logged in successfully.',
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

        Auth::login($user);
        return response()->json([
            'message' => 'Registered and logged in successfully.',
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

        // $token = $user->createToken('api-token')->plainTextToken;
        Auth::attempt($request->only('email', 'password'));
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Logged in successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
