<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'visit_date',
        'treatment',
        'sick_leave_id',
    ];

    /**
     * Get the patient associated with the visit.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the visit.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the sick leave associated with the visit, if any.
     */
    public function sickLeave()
    {
        return $this->belongsTo(SickLeave::class);
    }

    /**
     * Get the diagnoses associated with the visit.
     */
    public function diagnoses()
    {
        return $this->belongsToMany(Diagnosis::class)->withTimestamps();
    }

}