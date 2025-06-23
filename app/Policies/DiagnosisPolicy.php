<?php

namespace App\Policies;

use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiagnosisPolicy
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
        // Doctors can view all diagnoses, but patients can view only their own
        // diagnoses if they have a relationship with the diagnosis model.
        if ($user->isDoctor()) {
            return true;
        }
        if ($user->isPatient() && $user->patient->diagnoses()->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Diagnosis $diagnosis): bool
    {
        // Doctors can view it, but patients can only view if it's their own
        if ($user->isDoctor()) {
            return true;
        }
        if ($user->isPatient() && $user->patient->diagnoses()->where('id', $diagnosis->id)->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Diagnosis $diagnosis): bool
    {
        // Doctors can update diagnoses, but patients cannot
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Diagnosis $diagnosis): bool
    {
        // Only doctors can delete diagnoses
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Diagnosis $diagnosis): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Diagnosis $diagnosis): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the most common diagnoses.
     */
    public function viewMostCommon(User $user): bool
    {
        // Only doctors can view the most common diagnoses
        return $user->isDoctor();
    }
}
