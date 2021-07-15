<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'student_number' => $this->username,
            'subjects' => $this->subjects,
            'birthdate' => Carbon::parse($this->birthdate)->format('F j, Y'),
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'status' => $this->user_status,
            'course' => $this->course->title,
            'course_id' => $this->course->id,
            'birthdate_real' => $this->birthdate,
        ];
    }
}
