<?php

namespace App\Http\Resources;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $otherSubjects = \App\Models\SubjectTeacher::where('teacher_id','!=',auth()->user()->teacher->id)->pluck('subject_id');
        return [
            'title' => $this->title,
            'subjects' => $this->subjects()
                ->leftJoin('student_subjects','subjects.id','student_subjects.subject_id')
                ->select([
                    'subjects.id',
                    'subjects.title',
                    'subjects.code',
                    DB::raw('COUNT(student_subjects.student_id) DIV 3 as students')
                ])
                ->groupBy('subjects.id')
                ->whereNotIn('subjects.id',$otherSubjects)->get()
        ];
    }
}
