<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'student_id' => $this->id,
            'grade' => $this->grade,
            'name' => $this->user->fullName,
            'student_number' => $this->user->username,
            'status' => $this->status,
        ];
    }
}
