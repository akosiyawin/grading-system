<?php

namespace App\Http\Controllers;

use App\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

//    public function __construct()
//    {
//        $this->middleware(['auth','student']);
//    }

    public function print()
    {
        return view('student.print');
    }

    public function index()
    {
        return view('student.index');
    }


    public function announcementIndex()
    {
        return view('student.announcement');
    }

    public function Student_information(request $request, $user_id = "")
    {

        $student_information = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('courses', 'students.course_id', '=', 'courses.id')
            ->select(
                'username',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'courses.title',
            )
//            ->where('users.role_id', '=', Base::STUDENT_ROLE_ID)
            ->where('users.id', $user_id)
//            ->select()
            ->get();

//        dd($student_information);

        $first_name = $student_information->pluck('first_name');
        $first_name = str_replace('["', "", "$first_name");
        $first_name = str_replace('"]', "", "$first_name");
        $middle_name = $student_information->pluck('middle_name');
        $middle_name = str_replace('["', "", "$middle_name");
        $middle_name = str_replace('"]', "", "$middle_name");
        $last_name = $student_information->pluck('last_name');
        $last_name = str_replace('["', "", "$last_name");
        $last_name = str_replace('"]', "", "$last_name");

        $student_name = $last_name . ", " . $first_name . " " . $middle_name;

        if (count($student_information)) {
            return response(['student_id' => $student_information->pluck('username'),
                'student_name' => $student_name,
                'course' => $student_information->pluck('title'),
            ], 200);
        } else {
            return response(['Message' => 'Student Information not found'], 404);
        }
    }

    public function Grades(request $request, $user_id = "", $semester = "", $year = "")
    {
        $grades = DB::table('students')
            ->join('student_subjects', 'students.id', '=', 'student_subjects.student_id')
            ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
            ->join('users', 'subject_teachers.teacher_id', '=', 'users.id')
            ->join('semesters', 'subject_teachers.semester_id', '=', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
            ->select(
                'subjects.code',
                'subjects.title',
                'student_subjects.grade',
                'subjects.units',
//               'semesters.id',
                DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as name'),
                'users.last_name',
                'users.first_name',
                'users.middle_name',
                'student_subjects.grade',
                'student_subjects.status',
                'semesters.title as semesterTitle',
                'school_years.year'
            )
            ->where('school_years.year', '=', $year)
            ->where('semesters.id', '=', $semester)
//            ->select()
            ->where('students.user_id', '=', $user_id)
            ->get();


        if (count($grades)) {
            return response($grades, 200);
        } else {
            return response(['Message' => 'No records found'], 404);
        }
    }

    public function activated_semester()
    {
        $activated_semester = DB::table('semesters')
            ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
            ->select(
                'semesters.id'
            )
            ->where('semesters.status', '=', 1)
            ->get();

        $semester_year = DB::table('semesters')
            ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
            ->Where('status', '=', 1)
            ->pluck('year');


        foreach ($semester_year as $key => $value) {
            $year_start = $value;
            $year_end = $value;
        }

        $year_end = $year_end += 1;

        $semester_year = $year_start ." - " . $year_start ;

        if (count($activated_semester)) {
            return response(["activated_semester"=>$activated_semester,"school_year"=>$semester_year], 200);
        } else {
            return reponse(['Message'=>'invalid request'],200);
        }

    }

    public function Totalfooter(request $request, $user_id = "", $semester = "", $year = "")
    {
        $student_information = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('courses', 'students.course_id', '=', 'courses.id')
            ->select(
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.username',
                'students.birthdate',
                'courses.title',
            )
            ->where('users.id', '=', $user_id)
            ->where('users.role_id', '=', Base::STUDENT_ROLE_ID)
//            ->select()
            ->get();

        if (count($student_information)) {
            $average = DB::table('student_subjects')
                ->join('students', 'student_subjects.student_id', '=', 'students.id')
                ->join('semesters', 'student_subjects.semester_id', '=', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
                ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select(
                    DB::raw('SUM(grade) / count(subjects.id) as average')
                )
                ->where('school_years.year', '=', $year)
                ->where('semesters.id', '=', $semester)
                ->where('students.user_id', '=', $user_id)
//                 ->select()
                ->get();

//            print_r($average);
            $average = $average->pluck('average');

            foreach ($average as $key => $value) {
                $average = $value;
            }

            if ($average >= 98) {
                $average = "1.0";
            } elseif ($average >= 95) {
                $average = "1.25";
            } elseif ($average >= 92) {
                $average = "1.50";
            } elseif ($average >= 89) {
                $average = "1.75";
            } elseif ($average >= 86) {
                $average = "2.0";
            } elseif ($average >= 83) {
                $average = "2.25";
            } elseif ($average >= 80) {
                $average = "2.50";
            } elseif ($average >= 77) {
                $average = "2.75";
            } elseif ($average >= 75) {
                $average = "3.0";
            } else {
                $average = "5.0";
            }

            $units = DB::table('student_subjects')
                ->join('students', 'student_subjects.student_id', '=', 'students.id')
                ->join('semesters', 'student_subjects.semester_id', '=', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
                ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select(
                    'units'
                )
                ->where('school_years.year', '=', $year)
                ->where('semesters.id', '=', $semester)
                ->where('students.user_id', '=', $user_id)
//                ->select()
                ->get();

            $units = $units->pluck('units');
            $total_lecture = 0;
            $total_lab = 0;

            foreach ($units as $key => $value) {
                $units = $value;
                $units = explode('(', $units);
                $units = str_replace(')', '', $units);

                $lecture_units = $units[0];
                $total_lecture += $lecture_units;

                $lab_units = $units[1];
                $total_lab += $lab_units;

            }

            $units = $total_lecture . "(" . $total_lab . ")";

            return response(['Average' => $average, 'units' => $units], 200);
        } else {
            return response(['Message' => 'No records found'], 404);
        }


    }

    public function CopyOfGrades(request $request, $user_id = "", $semester_id = "", $year = "")
    {
        $student_information = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('courses', 'students.course_id', '=', 'courses.id')
            ->select(
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.username',
                'students.birthdate',
                'courses.title',
            )
            ->where('users.id', '=', $user_id)
            ->where('users.role_id', '=', Base::STUDENT_ROLE_ID)
            ->get();


        if (count($student_information)) {
            $activated_semester = DB::table('semesters')
                ->Where('status', '=', 1)
                ->pluck('id');

            $semester = DB::table('semesters')
//                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select()
                ->Where('status', '=', 1)
                ->get();

            $semester_year = DB::table('semesters')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->Where('status', '=', 1)
                ->pluck('year');


            foreach ($semester_year as $key => $value) {
                $year_start = $value;
                $year_end = $value;
            }

            $year_end = $year_end += 1;

            $semester_year = $year_start . " - " . $year_end;


            $grades = DB::table('students')
                ->join('student_subjects', 'students.id', '=', 'student_subjects.student_id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
                ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
                ->join('semesters', 'subject_teachers.semester_id', '=', 'semesters.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select(
                    'subjects.code',
                    'subjects.title',
                    'student_subjects.grade',
                    'subjects.units',
//                    'semesters.id',
                )
                ->where('school_years.year', '=', $year)
                ->where('students.user_id', '=', $user_id)
                ->where('semesters.id', '=', $semester_id)
//            ->select()
                ->get();

            $units = DB::table('student_subjects')
                ->join('students', 'student_subjects.student_id', '=', 'students.id')
                ->join('semesters', 'student_subjects.semester_id', '=', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
                ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
//                ->select(
//                    'units'
//                )
//                ->where('student_subjects.semester_id', '=', )
//                ->where('students.user_id', '=', $user_id)
                ->where('school_years.year', '=', $year)
                ->where('students.user_id', '=', $user_id)
                ->where('semesters.id', '=', $semester_id)
                ->select()
                ->get();

            $units = $units->pluck('units');
            $total_lecture = 0;
            $total_lab = 0;

            foreach ($units as $key => $value) {
                $units = $value;
                $units = explode('(', $units);
                $units = str_replace(')', '', $units);

                $lecture_units = $units[0];
                $total_lecture += $lecture_units;

                $lab_units = $units[1];
                $total_lab += $lab_units;

            }

            $units = $total_lecture . "(" . $total_lab . ")";

            $average = DB::table('student_subjects')
                ->join('students', 'student_subjects.student_id', '=', 'students.id')
                ->join('semesters', 'student_subjects.semester_id', '=', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', '=', 'subject_teachers.id')
                ->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
                ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
                ->select(
                    DB::raw('SUM(grade) / count(subjects.id) as average')
                )
                ->where('student_subjects.semester_id', '=', $activated_semester)
                ->where('school_years.year', '=', $year)
                ->where('students.user_id', '=', $user_id)
                ->where('semesters.id', '=', $semester_id)
//                 ->select()
                ->get();

            $average = $average->pluck('average');

            foreach ($average as $key => $value) {
                $average = $value;
            }

            if ($average >= 98) {
                $average = "1.0";
            } elseif ($average >= 95) {
                $average = "1.25";
            } elseif ($average >= 92) {
                $average = "1.50";
            } elseif ($average >= 89) {
                $average = "1.75";
            } elseif ($average >= 86) {
                $average = "2.0";
            } elseif ($average >= 83) {
                $average = "2.25";
            } elseif ($average >= 80) {
                $average = "2.50";
            } elseif ($average >= 77) {
                $average = "2.75";
            } elseif ($average >= 75) {
                $average = "3.0";
            } else {
                $average = "5.0";
            }


            return response([
                'student_information' => $student_information,
                'activated_semester' =>
                    $semester,
                $grades,
                'units' => $units,
                'average' => $average,
                'semester_year' => $semester_year
            ], 200);
//            return response($grades,200);
        } else {
            return response(['Message' => 'No records found'], 404);
        }
    }

}
