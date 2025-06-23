<?php

namespace App\Policies;

use App\Models\SickLeave;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SickLeavePolicy
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
        // Doctors can view all sick leaves, but patients can only view their own
        if ($user->isDoctor()) {
            return true;
        }
        if ($user->isPatient() && $user->patient->sickLeaves()->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SickLeave $sickLeave): bool
    {
        // Doctors can view it, but patients can only view if it's their own
        if ($user->isDoctor()) {
            return true;
        }
        if ($user->isPatient() && $user->patient->sickLeaves()->where('id', $sickLeave->id)->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only doctors can create sick leaves
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SickLeave $sickLeave): bool
    {
        // Doctors can update only the sick leaves they created
        return $user->isDoctor() && $user->id === $sickLeave->visit->doctor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SickLeave $sickLeave): bool
    {
        // Doctors can delete only the sick leaves they created
        return $user->isDoctor() && $user->id === $sickLeave->visit->doctor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SickLeave $sickLeave): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SickLeave $sickLeave): bool
    {
        return false;
    }
}
