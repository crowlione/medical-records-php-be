<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SickLeave extends Model
{
    /** @use HasFactory<\Database\Factories\SickLeaveFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'from_date',
        'day_count',
    ];

    /**
     * Get the visit associated with the sick leave.
     */
    public function visit()
    {
        return $this->hasOne(Visit::class);
    }
}
