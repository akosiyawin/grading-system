<?php

namespace App\Http\Resources;

use App\Models\SubjectTeacher;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            ->where('student_subjects.status', 0)
            ->where(['semesters.status' => 1])
            ->where('subject_teachers.teacher_id', $this->id)
            ->count();
        $forApprovalCount +=
            SubjectTeacher::join('student_subjects', 'subject_teachers.id', '=', 'student_subjects.subject_teacher_id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->join('resubmissions', 'resubmissions.student_subject_id', 'student_subjects.id')
            ->where('student_subjects.status', 1)
            ->where(['semesters.status' => 1])
            ->where('subject_teachers.teacher_id', $this->id)
            ->count();
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'username' => $this->username,
            'subject' => $this->subjects()
                ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
                ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
                ->where(['semesters.status' => 1])->get()->count(),
            'student' => SubjectTeacher::join('semesters', 'subject_teachers.semester_id', 'semesters.status')
                ->join('student_subjects', 'subject_teachers.id', 'student_subjects.subject_teacher_id')
                ->where([
                    'semesters.status' => 1,
                    'subject_teachers.teacher_id' => $this->id
                ])->count(),
            'status' => $this->user->status,
            'approval_count' => $forApprovalCount
        ];
    }
}
