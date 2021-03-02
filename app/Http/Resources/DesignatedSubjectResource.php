<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignatedSubjectResource extends JsonResource
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
            'teacher_id' => $this->teacher_id,
            'subject_id' => $this->subject_id,
            'code'=> $this->code,
            'title'=> $this->title." - ". $this->remarks,
            'units'=> $this->units,
            'user_id'=> $this->user_id,
            'name'=> $this->first_name." ".$this->last_name,
        ];
    }
}
