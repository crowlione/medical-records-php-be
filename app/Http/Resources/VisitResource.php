<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visit_date' => $this->visit_date,
            'treatment' => $this->treatment,
            'doctor' => $this->doctor,
            'patient' => $this->patient,
            'sick_leave' => $this->sickLeave,
            'diagnoses' => DiagnosisResource::collection($this->whenLoaded('diagnoses')),
        ];
    }
}
