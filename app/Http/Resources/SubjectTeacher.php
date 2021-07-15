<?php

namespace App\Http\Resources;

use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTeacher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /*
         * SELECT * FROM subject_teachers inner join student_subjects
            on subject_teachers.subject_id = student_subjects.subject_id
            inner join semesters on student_subjects.semester_id = semesters.id
            where teacher_id = 1 and semesters.status = 1
         * */
        return [
            'subject_id' => $this->subject_id,
            'title' => $this->title,
            'remarks' => $this->remarks,
            'code' => $this->code,
            'students' =>  StudentSubject::join('semesters', 'student_subjects.semester_id', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
                ->where('semesters.status', 1)
                ->where('subject_teachers.id', $this->subject_id)
                ->where('subject_teachers.teacher_id', auth()->user()->teacher->id)
                ->count()
        ];
    }
}
