<?php

namespace App\Http\Controllers;

use App\Base;
use App\Http\Resources\TitleOnlyResource;
use App\Http\Traits\RegistrarApi;
use App\Http\Traits\RegistrarViews;
use App\Models\Course;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\fileExists;


class RegistrarController extends Controller
{
    use RegistrarApi, RegistrarViews;
    public function __construct()
    {
        $this->middleware(['auth','registrar']);
    }

    public function studentProfile(Student $student)
    {
        $schoolYears = StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
            ->join('subjects','subject_teachers.subject_id','subjects.id')
            ->join('semesters','student_subjects.semester_id','semesters.id')
            ->join('school_years','semesters.school_year_id','school_years.id')
            ->where('student_subjects.student_id',$student->id)
            ->groupBy('school_years.id')
            ->pluck('school_years.year','school_years.id');

        return view('student.profile',compact('schoolYears','student'));
    }

    public function fetchSemesterForYear(SchoolYear $schoolYear)
    {
        $semesters = StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
            ->join('semesters','student_subjects.semester_id','semesters.id')
            ->join('school_years','semesters.school_year_id','school_years.id')
            ->where('school_years.id',$schoolYear->id)
            ->groupBy('semesters.id')
            ->pluck('semesters.title','semesters.id');
//        ->get();
//        dd($semesters);
        return response()->json([
            'message' => "Semesters For School Year GET Successful!",
            'data' => $semesters
        ]);
    }

    public function fetchGradeForSemester(SchoolYear $schoolYear, Semester $semester, Student $student)
    {
        $grades = StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
            ->join('subjects','subject_teachers.subject_id','subjects.id')
            ->join('teachers','subject_teachers.teacher_id','teachers.id')
            ->join('users','teachers.user_id','users.id')
            ->join('semesters','student_subjects.semester_id','semesters.id')
            ->join('school_years','semesters.school_year_id','school_years.id')
            ->where('school_years.id',$schoolYear->id)
            ->where('semesters.id',$semester->id)
            ->where('student_subjects.student_id',$student->id)
            ->select([
                'student_subjects.grade',
                'subjects.code',
                'subjects.title',
                'subjects.units',
                'student_subjects.status',
                DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as teacher')
            ])
            ->get();
        return response()->json([
            'message' => "Semesters For School Year GET Successful!",
            'data' => $grades
        ]);
    }

}
