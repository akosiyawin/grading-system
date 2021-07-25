<?php

namespace App\Http\Resources;

use App\Models\SubjectTeacher;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $forApprovalCount = SubjectTeacher::join('student_subjects', 'subject_teachers.id', '=', 'student_subjects.subject_teacher_id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->where(['subject_teachers.id' => $this->subject_id])
            ->where('student_subjects.status', 0)
            ->where(['semesters.status' => 1])
            ->count();

        $forApprovalCount +=
            SubjectTeacher::join('student_subjects', 'subject_teachers.id', '=', 'student_subjects.subject_teacher_id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->join('resubmissions', 'resubmissions.student_subject_id', 'student_subjects.id')
            ->where(['subject_teachers.id' => $this->subject_id])
            ->where('student_subjects.status', 1)
            ->where(['semesters.status' => 1])
            ->count();
        return [
            'code' => $this->code,
            'title' => $this->title,
            'subject_id' => $this->subject_id,
            'approval_count' => $forApprovalCount
        ];
    }
}
