<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'id' => $this->subject_id,
            'code' => $this->code,
            'title' => $this->title,
            'units' => $this->units,
            'department' => $this->department,
            'department_id' => $this->department_id
        ];
    }
}
