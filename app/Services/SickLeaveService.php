<?php

namespace App\Services;

use App\Models\SickLeave;

class SickLeaveService
{
    public function __construct(private SickLeave $sickLeave)
    {
    }

    public function createSickLeave(array $data): SickLeave
    {
        $sickLeave = $this->sickLeave->create([
            'from_date' => $data['from_date'],
            'day_count' => $data['day_count'],
        ]);

        return $sickLeave;
    }

    public function getSickLeaveById($sickLeaveId): ?SickLeave
    {
        return $this->sickLeave->find($sickLeaveId);
    }

    public function updateSickLeave($sickLeaveId, array $data): SickLeave
    {
        $sickLeave = $this->getSickLeaveById($sickLeaveId);
        if (!$sickLeave) {
            throw new \Exception("Sick leave not found");
        }

        $sickLeave->from_date = $data['from_date'] ?? $sickLeave->from_date;
        $sickLeave->day_count = $data['day_count'] ?? $sickLeave->day_count;

        $sickLeave->save();

        return $sickLeave;
    }
}
