<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    /** @use HasFactory<\Database\Factories\DiagnosisFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the visits associated with the diagnosis.
     */
    public function visits()
    {
        return $this->belongsToMany(Visit::class)->withTimestamps();
    }
}
