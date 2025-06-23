<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    //admin can do everything
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // doctors can view all patients, but patients can't
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // doctors can view all patients, but patients can only view their own record
        return $user->isDoctor() || $user->id === $patient->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // only doctors can create patients
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // doctors can update all patients, but patients can only update their own record
        return $user->isDoctor() && $user->id === $patient->user()->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // only doctors can delete patients
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view all patients with a specific diagnosis.
     */
    public function viewDiagnosis(User $user): bool
    {
        // only doctors can view patients with a specific diagnosis
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can view all patients with a specific GP.
     */
    public function viewGp(User $user): bool
    {
        // only doctors can view patients with a specific GP
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can count patients by GPs.
     */
    public function countByGps(User $user): bool
    {
        // only doctors can count patients by GPs
        return $user->isDoctor();
    }
}
