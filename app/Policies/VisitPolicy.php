<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Auth\Access\Response;

class VisitPolicy
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
        // Any user can view, patients can only view their own visits
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Visit $visit): bool
    {
        // Doctors can view it, but patients can only view if it's their own
        if ($user->isDoctor()) {
            return true;
        }
        if ($user->isPatient() && $user->patient->visits()->where('id', $visit->id)->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any user can create a visit
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Visit $visit): bool
    {
        // doctors and patients can update theur own visits
        if ($user->isDoctor() && $user->id === $visit->doctor_id) {
            return true;
        }
        if ($user->isPatient() && $user->id === $visit->patient_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Visit $visit): bool
    {
        // Both patients and doctors can delete(cancel) their own visits
        if ($user->isDoctor() && $user->id === $visit->doctor_id) {
            return true;
        }
        if ($user->isPatient() && $user->id === $visit->patient_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Visit $visit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view all visits of a doctor.
     */
    public function viewAllDoctorVisits(User $user, int $doctorId): bool
    {
        // Only doctors can view all visits of a doctor
        return $user->isDoctor();
    }
}
