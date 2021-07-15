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
        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $yearTitle = "School Year of {$semester->schoolYear->year} - {$semester->title}";
        } else {
            $yearTitle = null;
        }

        \View::share('yearTitle', $yearTitle);
        $this->middleware(['auth', 'registrar', 'status']);
    }

    public function studentProfile(Student $student)
    {
        $schoolYears = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('student_subjects.student_id', $student->id)
            ->groupBy('school_years.id')
            ->pluck('school_years.year', 'school_years.id');

        return view('student.profile', compact('schoolYears', 'student'));
    }

    public function fetchSemesterForYear(SchoolYear $schoolYear)
    {
        $semesters = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('school_years.id', $schoolYear->id)
            ->groupBy('semesters.id')
            ->pluck('semesters.title', 'semesters.id');
        //        ->get();
        //        dd($semesters);
        return response()->json([
            'message' => "Semesters For School Year GET Successful!",
            'data' => $semesters
        ]);
    }

    private function isV1Api($semester)
    {
        return (int)$semester->schoolYear->year === 2020 &&   $semester->title === "First Semester";
    }

    private function gradeDecider($initial_grade)
    {
        $grade = (float)$initial_grade;
        if ($grade == 4) {
            return "DRP";
        } else if ($grade == 0) {
            return "INC";
        } else if ($grade === 5) {
            return "5.00";
        } else {
            return $grade;
        }
    }


    private function remarksDecider($grade)
    {
        $grade = (float)$grade;

        if ($grade === 4) {
            return "DRP";
        } else if ($grade === 0) {
            return "INC";
        } else if ($grade === 5) {
            return "Failed";
        } else {
            return "Passed";
        }
    }

    private function remarksDeciderV1($grade)
    {
        if (floatval($grade) <= 74) {
            return "Failed";
        } else {
            return "Passed";
        }
    }

    private function gradeDeciderV1($initial_grade)
    {
        $grade = floatval($initial_grade);
        if ($grade >= 98) {
            return "1.00";
        } else if ($grade >= 95) {
            return "1.25";
        } else if ($grade >= 92) {
            return "1.50";
        } else if ($grade >= 88) {
            return "1.75";
        } else if ($grade >= 85) {
            return "2.00";
        } else if ($grade >= 82) {
            return "2.25";
        } else if ($grade >= 79) {
            return "2.50";
        } else if ($grade >= 76) {
            return "2.75";
        } else if ($grade >= 74.5) {
            return "3.00";
        } else if ($grade == 0) {
            return "INC";
        } else if ($grade == 4) {
            return "DRP";
        } else {
            /*5.00*/
            return "5.00";
        }
    }

    public function fetchGradeForSemester(SchoolYear $schoolYear, Semester $semester, Student $student)
    {
        $isV1Api = $this->isV1Api($semester);
        $grades = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('teachers', 'subject_teachers.teacher_id', 'teachers.id')
            ->join('users', 'teachers.user_id', 'users.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('school_years.id', $schoolYear->id)
            ->where('semesters.id', $semester->id)
            ->where('student_subjects.student_id', $student->id)
            ->select([
                'student_subjects.grade',
                'subjects.code',
                'subjects.title',
                'subjects.units',
                'student_subjects.status',
                DB::raw('CONCAT(users.last_name,", ",users.first_name," ",IFNULL(users.middle_name, "")) as teacher')
            ])
            ->get();
        $totalGrade = 0;
        $lecTotal = 0;
        $labTotal = 0;
        $recordLength = count($grades);
        $hasIncomplete = false;
        foreach ($grades as &$item) {
            $grade = $item['grade'];
            //Sysgrade converter
            $item['grade'] = $isV1Api ? $this->gradeDeciderV1($item['grade']) : $this->gradeDecider($item['grade']);
            if ($item['grade'] === 'INC') {
                $item['remarks'] = 'INCOMPLETE';
                $hasIncomplete = true;
            } else if ($item['grade'] === 'DRP') {
                $item['remarks'] = 'DROPPED';
            } else {
                $item['remarks'] = $isV1Api ? $this->remarksDeciderV1($grade) : $this->remarksDecider($grade);
            }

            if ((int)$grade !== 4) {
                $totalGrade += (float)($item['grade'] ?? 0);
                $units = explode(' ', str_replace(['(', ')'], '', $item['units']));
                $lecTotal += isset($units[0]) ? (int)$units[0] : 0;
                $labTotal += isset($units[1]) ? (int)$units[1] : 0;
            } else {
                $recordLength--;
            }
        }

        if ($recordLength) {
            return response()->json([
                'message' => "Grades for semester GET Successful!",
                'data' => $grades,
                'totalUnits' => $lecTotal . ($labTotal ? " ({$labTotal})" : ''),
                'totalGrade' => $hasIncomplete ? "INC" : number_format($totalGrade / $recordLength, 2)
            ]);
        }

        return response()->json([
            'message' => "Your grades are on process for School Year " . $schoolYear->year . " - " . $semester->title,
            'data' => [],
        ]);
    }
}
