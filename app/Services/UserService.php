<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(private User $user)
    {
    }
    public function createPatientUser(array $data): User
    {
        $user = $this->user->create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'patient',
        ]);

        $user->patient()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
            'egn' => $data['egn'],
            'has_paid_insurance' => $data['has_paid_insurance'] ?? true,
            'gp_id' => $data['gp_id'] ?? null,
        ]);

        return $user;
    }

    public function createDoctorUser(array $data): User
    {
        $user = $this->user->create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'doctor',
        ]);

        $user->doctor()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
            'uin' => $data['uin'],
            'is_gp' => $data['is_gp'],
        ]);

        return $user;
    }
}
