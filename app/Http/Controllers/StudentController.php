<?php

namespace App\Http\Controllers;

use App\Base;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'student', 'status']);
    }

    public function printGrade(SchoolYear $schoolYear, Semester $semester)
    {
        $isV1Api = $this->isV1Api($semester);
        $student = auth()->user()->student;
        $grades = $this->getgrades($schoolYear, $semester, $student);
        $totalGrade = 0;
        $lecTotal = 0;
        $labTotal = 0;
        $recordLength = count($grades);
        $hasIncomplete = false;
        foreach ($grades as &$item) {
            $grade = $item['grade'];
            $item['grade'] = $isV1Api ? $this->gradeDeciderV1($item['grade']) : $this->gradeDecider($item['grade']);
            if ($item['grade'] === 'INC') {
                $item['remarks'] = 'INCOMPLETE';
                $hasIncomplete = true;
            } else if ($item['grade'] === 'DRP') {
                $item['remarks'] = 'DROPPED';
            } else {
                $item['remarks'] = $isV1Api ? $this->remarksDeciderV1($grade) : $this->remarksDecider($grade);
            }

            if (intval($grade) !== 4) {
                $totalGrade += floatval($item['grade'] ?? 0);
                $units = explode(' ', str_replace(['(', ')'], '', $item['units']));
                $lecTotal += isset($units[0]) ? (int)$units[0] : 0;
                $labTotal += isset($units[1]) ? (int)$units[1] : 0;
            } else {
                $recordLength--;
            }
        }
        $user = auth()->user();
        $info = json_encode([
            'name' => $user->fullName,
            'student_number' => $user->username,
            'course' => $user->student->course->title,
            'birthdate' => date('F d, Y', strtotime($user->student->birthdate))
        ]);
        if ($recordLength) {
            $data = $grades;
            $totalUnits = $lecTotal . ($labTotal ? " ({$labTotal})" : '');
            $totalGrade = $hasIncomplete ? "INC" : number_format($totalGrade / $recordLength, 2);
            $yearTitle = "School Year " . $schoolYear->year . ", " . $semester->title;
            return view('student.printGrade', compact('data', 'totalUnits', 'totalGrade', 'info', 'yearTitle'));
        }

        abort(404);
        return null;
    }

    public function print()
    {
        return view('student.print');
    }

    public function index()
    {
        return view('student.index');
    }

    public function myGrade()
    {
        return view('student.myGrade');
    }

    public function mySchoolYear()
    {
        $student = auth()->user()->student;
        $schoolYears = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('student_subjects.student_id', $student->id)
            ->where('student_subjects.status', 1)
            ->select(['school_years.id', 'school_years.year'])
            ->groupBy('school_years.id')
            ->get();
        return response()->json([
            'message' => "My School Years GET Successful!",
            'data' => $schoolYears
        ]);
    }

    public function mySemester(SchoolYear $schoolYear)
    {
        $semesters = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('school_years.id', $schoolYear->id)
            ->where('student_subjects.status', 1)
            ->select(['semesters.id', 'semesters.title'])
            ->groupBy('semesters.id')
            ->get();

        return response()->json([
            'message' => "My Semesters For School Year GET Successful!",
            'data' => $semesters
        ]);
    }

    private function getgrades(SchoolYear $schoolYear, Semester $semester, Student $student)
    {
        return StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('teachers', 'subject_teachers.teacher_id', 'teachers.id')
            ->join('users', 'teachers.user_id', 'users.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->where('school_years.id', $schoolYear->id)
            ->where('semesters.id', $semester->id)
            ->where('student_subjects.status', 1)
            ->where('student_subjects.student_id', $student->id)
            ->select([
                'student_subjects.grade',
                'subjects.code',
                'subjects.title',
                'subjects.units',
                'student_subjects.status',
                DB::raw('CONCAT(LEFT(users.first_name,1),". ",users.last_name) as teacher')
            ])
            ->get();
    }

    private function isV1Api($semester)
    {
        return (int)$semester->schoolYear->year === 2020 &&   $semester->title === "First Semester";
    }

    public function myGradeForSemester(SchoolYear $schoolYear, Semester $semester)
    {
        $isV1Api = $this->isV1Api($semester);
        $student = auth()->user()->student;
        $grades = $this->getgrades($schoolYear, $semester, $student);
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
            return sprintf("%0.1f", $grade);
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
            return "1.0";
        } else if ($grade >= 95) {
            return "1.25";
        } else if ($grade >= 92) {
            return "1.5";
        } else if ($grade >= 88) {
            return "1.75";
        } else if ($grade >= 85) {
            return "2.0";
        } else if ($grade >= 82) {
            return "2.25";
        } else if ($grade >= 79) {
            return "2.5";
        } else if ($grade >= 76) {
            return "2.75";
        } else if ($grade >= 74.5) {
            return "3.0";
        } else if ($grade == 0) {
            return "INC";
        } else if ($grade == 4) {
            return "DRP";
        } else {
            /*5.00*/
            return "5.0";
        }
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
            return response([
                'student_id' => $student_information->pluck('username'),
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
            ->join('teachers', 'subject_teachers.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->join('semesters', 'subject_teachers.semester_id', '=', 'semesters.id')
            ->join('school_years', 'semesters.school_year_id', '=', 'school_years.id')
            ->select(
                'subjects.code',
                'subjects.title',
                'student_subjects.grade',
                'subjects.units',
                'semesters.id',
                DB::raw('CONCAT(users.last_name,", ",users.first_name," ",users.middle_name) as name'),
                'users.last_name',
                'users.first_name',
                'users.middle_name',
                'student_subjects.grade',
                'student_subjects.status',
                'semesters.title as semesterTitle',
                'school_years.year',
                'school_years.id as year_id',
                'semesters.id as semester_id'
            )
            ->where('school_years.year', '=', $year)
            ->where('semesters.id', '=', $semester)
            //            ->select()
            ->where('students.user_id', '=', $user_id)
            ->where('student_subjects.status', '=', 1)
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

        $semester_year = $year_start . " - " . $year_start;

        if (count($activated_semester)) {
            return response(["activated_semester" => $activated_semester, "school_year" => $semester_year], 200);
        } else {
            return reponse(['Message' => 'invalid request'], 200);
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
                ->where('student_subjects.status', '=', 1)
                ->where('school_years.year', '=', $year)
                ->where('semesters.id', '=', $semester)
                ->where('students.user_id', '=', $user_id)
                ->where('student_subjects.grade', '<>', '4')
                ->where('student_subjects.grade', '<>', '0')
                //                 ->select()
                ->get();

            //            $average = $average->pluck('average')[0]; //Use this instead?
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
            } elseif ($average >= 88) {
                $average = "1.75";
            } elseif ($average >= 85) {
                $average = "2.0";
            } elseif ($average >= 82) {
                $average = "2.25";
            } elseif ($average >= 79) {
                $average = "2.50";
            } elseif ($average >= 76) {
                $average = "2.75";
            } elseif ($average >= 74.5) {
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
                ->where('student_subjects.status', '=', 1)
                //                ->select()
                ->get();

            $units = $units->pluck('units');
            $total_lecture = 0;
            $total_lab = 0;

            foreach ($units as $key => $value) {
                $units = $value;
                $units = explode('(', $units);
                $units = str_replace(')', '', $units);

                $lecture_units = $units[0] ?? 0;
                $total_lecture += $lecture_units;

                $lab_units = $units[1] ?? 0;
                $total_lab += $lab_units;
            }
            //
            $units = $total_lecture . "(" . $total_lab . ")";
            //            print_r($units);


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
                ->where('student_subjects.status', '=', 1)
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
                ->where('student_subjects.status', '=', 1)
                ->select()
                ->get();

            $units = $units->pluck('units');
            $total_lecture = 0;
            $total_lab = 0;

            foreach ($units as $key => $value) {
                $units = $value;
                $units = explode('(', $units);
                $units = str_replace(')', '', $units);

                $lecture_units = $units[0] ?? 0;
                $total_lecture += $lecture_units;

                $lab_units = $units[1] ?? 0;
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
                ->where('student_subjects.status', '=', 1)
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
            } elseif ($average >= 88) {
                $average = "1.75";
            } elseif ($average >= 85) {
                $average = "2.0";
            } elseif ($average >= 82) {
                $average = "2.25";
            } elseif ($average >= 79) {
                $average = "2.50";
            } elseif ($average >= 76) {
                $average = "2.75";
            } elseif ($average >= 74.5) {
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
                'semester_year' => $semester_year,
                //                'schoolyear_id' =>
            ], 200);
            //            return response($grades,200);
        } else {
            return response(['Message' => 'No records found'], 404);
        }
    }

    public function userInfo()
    {
        return response(['Message' => 'Authenticated User GET Successfully', 'data' => ['id' => auth()->user()->id]], 200);
    }

    public function authUser()
    {
        $user = auth()->user();
        $profile = [
            'student_number' => $user->username,
            'birthdate' => date('F d, Y', strtotime($user->student->birthdate)),
            'name' => $user->fullName,
            'status' => $user->status,
            'course' => $user->student->course->title
        ];
        return response(['Message' => 'Authenticated User GET Successfully', 'data' => $profile], 200);
    }
}
