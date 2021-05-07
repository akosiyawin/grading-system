<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentOfMySubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'student_id' => $this->student_id,
            'course' => $this->course,
            'student_number' => $this->student_number,
            'name' => $this->name,
            'status' => $this->status,
            'grade' => $this->grade,
            'grade_status' => $this->grade_status,
            'resubmission' => $this->resubmission,
        ];
    }
}
