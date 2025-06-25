<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'uin',
        'is_gp',
        'user_id',
    ];

    /**
     * Get the user that owns the doctor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialties associated with the doctor.
     */
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class)->withTimestamps();
    }

    /**
     * Get the patients associated with the doctor.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class, 'gp_id');
    }

    /**
     * Get the visits for the doctor.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
