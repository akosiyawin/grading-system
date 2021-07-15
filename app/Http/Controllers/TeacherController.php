<?php

namespace App\Http\Controllers;

use App\Base;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\RestrictStudentResource;
use App\Http\Resources\StudentGradeResource;
use App\Http\Resources\StudentOfMySubjectResource;
use App\Http\Resources\StudentWithoutSubjectResource;
use App\Http\Resources\SubjectTeacher;
use App\Models\Department;
use App\Models\Resubmission;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\SubjectTeacher as ModelsSubjectTeacher;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\RequiredIf;

class TeacherController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'teacher', 'status']);
    }

    public function dashboard()
    {
        return view("homes.teacher");
    }

    public function createSubject()
    {
        $display = $this->displayYear();
        return view('teacher.subjects', compact('display'));
    }

    public function assignStudentsToSubjects()
    {
        $display = $this->displayYear();
        return view('teacher.students', compact('display'));
    }

    private function displayYear()
    {
        $year =  Semester::join('school_years', 'semesters.school_year_id', 'school_years.id')
            ->select(['semesters.title', 'school_years.year'])
            ->where('semesters.status', 1)
            ->first();
        $display = "School Year {$year->year} - {$year->title}";
        return $display;
    }

    public function index()
    {
        $display = $this->displayYear();
        return view('teacher.index', compact('display'));
    }

    public function Acquired_subjects()
    {
        $subjects = DB::table('subject_teachers')
            ->Join('subjects', 'subject_teachers.subject_id', '=', 'subjects.id')
            ->select('subjects.title')
            ->get();

        return response($subjects, 200);
    }

    public function View_subject_cs_department(request $request, $user_id = "")
    {
        $subjects = DB::table('subjects')
            ->join('departments', 'subjects.id', '=', 'departments.id')
            ->select('subjects.title')
            ->where('departments.title', '=', Base::CS_DEPT)
            ->where('departments.title', '=', Base::CS_DEPT)
            ->get();

        return response(['Subjects' => $subjects], 200);
    }

    public function View_subject_coe_department()
    {
        $subjects = DB::table('subjects')
            ->join('departments', 'subjects.id', '=', 'departments.id')
            ->select('subjects.title')
            ->where('departments.title', '=', Base::BS_COE)
            ->get();

        return response(['Subjects' => $subjects], 200);
    }


    public function departmentSubjectIndex()
    {
        $semID = Semester::where('semesters.status', 1)->first()->id;

        $mySubjects = ModelsSubjectTeacher::join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('departments', 'subjects.department_id', 'departments.id')
            ->select([
                'subject_teachers.id as subject_id', //subject_id on view
                // DB::raw('CONCAT(subjects.title," - ", subject_teachers.remarks) as title'),
                'subjects.code',
                'subjects.title',
                'subjects.units',
                'subject_teachers.remarks',
                'departments.title as department_title',
            ])
            ->where('subject_teachers.teacher_id', auth()->user()->teacher->id)
            ->where('subject_teachers.semester_id', $semID)
            ->get();

        $lists = Subject::join('departments', 'subjects.department_id', 'departments.id')
            ->select([
                'subjects.id as subject_id',
                'subjects.code',
                'subjects.title',
                'subjects.units',
                'departments.title as department_title',
            ])->get();

        return response()->json([
            'message' => "Department - Subjects GET Successful",
            'data' => $lists,
            'mySubjects' => $mySubjects
        ]);
    }


    public function updateSubjectStatus(Request $request, $subject)
    {
        $request->validate([
            'status' => 'required|numeric',
            'remarks' => 'required_unless:status,==,0'
        ]);

        /*Add subject*/
        if ($request->status) {
            // if (!$user->where('subject_teachers.subject_id', $subject->id)->exists()) {
            ModelsSubjectTeacher::create([
                'subject_id' => $subject,
                'teacher_id' => auth()->user()->teacher->id,
                'semester_id' => Semester::where('status', 1)->first()->id,
                'remarks' => $request->get('remarks')
            ]);
            // }
            return response()->json([
                'message' => "Subject Assigned Successfully",
            ]);
        } else { /*Remove Subject*/
            $subject = ModelsSubjectTeacher::find($subject);

            if (
                StudentSubject::join('semesters', 'student_subjects.semester_id', 'semesters.id')
                ->join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
                ->where('subject_teachers.id', $subject->id)
                ->where('semesters.status', 1)
                ->count() > 0
            ) {
                return response()->json([
                    'message' => "Subject has an existing students.",
                ], 400);
            }

            $subject->delete();
            return response()->json([
                'message' => "Subject Removed Successfully",
            ]);
        }
    }

    // public function mySubjects()
    // {
    //     $teacherID = auth()->user()->teacher->id;
    //     $subjects = auth()->user()->teacher->subjects()
    //         ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
    //         ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
    //         ->where('semesters.status', 1)
    //         ->where('subject_teachers.teacher_id', $teacherID)
    //         ->select([
    //             'subjects.id as subject_id',
    //             'subjects.code',
    //             DB::raw('CONCAT(subjects.title," - ", subject_teachers.remarks) as title'),
    //             'subjects.code',
    //         ])
    //         ->get();
    //     return response()->json([
    //         'message' => "Subjects GET Successfully",
    //         'data' => SubjectTeacher::collection($subjects)
    //     ]);
    // }

    public function mySubjects()
    {
        $teacherID = auth()->user()->teacher->id;
        $semID = Semester::where('semesters.status', 1)->first()->id;

        $subjects = ModelsSubjectTeacher::join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('departments', 'subjects.department_id', 'departments.id')
            ->select([
                'subject_teachers.id as subject_id', //subject_id on view
                // DB::raw('CONCAT(subjects.title," - ", subject_teachers.remarks) as title'),
                'subjects.code',
                'subjects.title',
                'subject_teachers.remarks',
                'subjects.units',
            ])
            ->where('subject_teachers.teacher_id', $teacherID)
            ->where('subject_teachers.semester_id', $semID)
            ->get();
        return response()->json([
            'message' => "Subjects GET Successfully",
            'data' => SubjectTeacher::collection($subjects)
        ]);
    }


    public function studentWithoutSubject(Request $request, $subject)
    {
        $request->validate([
            'rowsPerPage' => 'required|numeric',
            'page' => 'required|numeric',
            'search' => 'nullable|string'
        ]);
        $columns = [
            'username',
            'first_name',
            'last_name',
            'middle_name',
            'courses.title'
        ];

        $students = Student::join('users', 'students.user_id', 'users.id')
            ->join('courses', 'students.course_id', 'courses.id')
            ->orderBy('users.last_name');

        $search = $request->get('search') ?? '';
        $students->where(function ($q) use ($columns, $search) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%${search}%");
            }
        });

        $students->select([
            'students.id as student_id',
            'users.id as user_id',
            'courses.title as course'
        ])
            ->groupBy('users.id');

        if ($request->get('rowsPerPage') == 99) {
            $data = $students->get();
        } else {
            $data = $students->paginate($request->get('rowsPerPage'));
        }

        $studentSubjects = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id');
        $studentSubjects->join('subjects', 'subject_teachers.subject_id', 'subjects.id');;
        $studentSubjects->join('semesters', 'student_subjects.semester_id', 'semesters.id');
        $studentSubjects->where('semesters.status', 1);
        $studentSubjects->where('subject_teachers.id', $subject);
        $studentSubjects->where('subject_teachers.teacher_id', auth()->user()->teacher->id);
        $studentSubjects->groupBy('student_id', 'subject_id');
        $studentSubjects = $studentSubjects->pluck('student_id');

        return response()->json([
            'message' => "Subjects GET Successfully",
            'data' => StudentWithoutSubjectResource::collection($data),
            'takenStudents' => $studentSubjects
        ]);
    }

    public function storeStudentSubject(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*' => 'numeric|exists:students,id',
            'subject_id' => 'required|numeric|exists:subject_teachers,id'
        ]);
        $semesterID = Semester::where('status', 1)->first()->id;
        $subjectTeacherID = $request->subject_id;
        /*join('semesters','student_subjects.semester_id','semesters.id')
            ->where('student_id', $student)->where('semesters.status',1)*/
        foreach ($validated['students'] as $student) {
            /*Only insert student once per subject, just in case someone bypassed the security*/
            if (!StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
                ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
                ->where('student_id', $student)
                ->where('semesters.status', 1)
                ->where('subject_teacher_id', $subjectTeacherID)->exists()) {
                StudentSubject::create([
                    'student_id' => $student,
                    'subject_teacher_id' => $subjectTeacherID,
                    'semester_id' => $semesterID
                ]);
            }
        }

        return response()->json([
            'message' => "Students has been assigned to subject successfully!",
        ]);
    }

    /* Not Used */
    public function destroyStudentSubject(Request $request, $subject)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*' => 'required|numeric|exists:students,id'
        ]);
        $students = $validated['students'];

        /*Delete student and subject relation*/
        /*todo consider deleting if subject is approved  -restrict*/
        $restricted = StudentSubject::whereIn('student_id', $students)->where('subject_teacher_id', $subject)
            ->select(['student_id'])
            ->where('status', 1)->get();

        StudentSubject::whereIn('student_id', $students)->where('subject_teacher_id', $subject)
            ->where('status', 0)
            ->delete();

        return response()->json([
            'message' => "Students has been deleted to subject successfully!",
            'restricted' => RestrictStudentResource::collection($restricted)
        ]);
    }

    public function studentOfMySubject($subject, Request $request)
    {
        $studentSubjects = StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id');
        $studentSubjects->join('subjects', 'subject_teachers.subject_id', 'subjects.id');
        $studentSubjects->join('semesters', 'student_subjects.semester_id', 'semesters.id');
        $studentSubjects->join('students', 'student_subjects.student_id', 'students.id');
        $studentSubjects->join('users', 'students.user_id', 'users.id');
        $studentSubjects->join('courses', 'students.course_id', 'courses.id');
        $studentSubjects->leftJoin('resubmissions', 'student_subjects.id', 'resubmissions.student_subject_id');
        $studentSubjects->select([
            'users.id as user_id',
            'students.id as student_id',
            'courses.title as course',
            'users.username as student_number',
            'student_subjects.grade',
            'student_subjects.status as grade_status',
            'users.status',
            'resubmissions.grade as resubmission',
            DB::raw('CONCAT(users.last_name,", ",users.first_name," ",IFNULL(users.middle_name, "")) as name')
        ]);

        $columns = [
            'username',
            'first_name',
            'last_name',
            'middle_name',
            'courses.title'
        ];
        $search = $request->get('search') ?? '';
        $studentSubjects->where(function ($q) use ($columns, $search) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%${search}%");
            }
        });

        $studentSubjects->where('semesters.status', 1);
        $studentSubjects->where('subject_teachers.id', $subject);
        $studentSubjects->where('teacher_id', auth()->user()->teacher->id);
        $studentSubjects->orderBy('users.last_name');
        $studentSubjects->groupBy('student_id', 'subject_id');
        if ($request->get('rowsPerPage') == 99) {
            $data = $studentSubjects->get();
        } else {
            $data = $studentSubjects->paginate($request->get('rowsPerPage'));
        }

        return response()->json([
            'message' => "Student Of Subjects GET successfully!",
            'data' => StudentOfMySubjectResource::collection($data)
        ]);
    }

    public function studentGrade(Student $student, Subject $subject)
    {
        $grades = $student->subjects()
            ->join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->where('subjects.id', $subject->id)
            ->where('subject_teachers.id', auth()->user()->teacher->id)
            ->where('semesters.status', 1)
            ->select([
                'student_subjects.grade',
                'student_subjects.status as approval_status'
            ])
            ->first();
        return response()->json([
            'message' => "Student Subject Grades GET successfully!",
            'data' => $grades
        ]);
    }

    //    public function updateGrade(Request $request, Student $student, Subject $subject)
    //    {
    //        $validated = $request->validate([
    //            'grade' => 'nullable|numeric|min:' . Base::MIN_STUDENT_GRADE . "|max:" . Base::MAX_STUDENT_GRADE,
    //        ]);
    //        $subjectTeacherID = \App\Models\SubjectTeacher::where('subject_id',$subject->id)
    //            ->where('teacher_id',auth()->user()->teacher->id)->first()->id;
    //
    //        $subjectID = $subject->id;
    //        $grade = $student->subjects()
    //            ->join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
    //            ->where('subject_teachers.id', $subjectTeacherID)
    //            ->where('subject_teachers.subject_id', $subjectID)->first();
    //        /*update only the subject that are not approved*/
    //        if (!$grade->status) {
    //            $student->subjects()
    //                ->join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
    //                ->where('subject_teachers.id', $subjectTeacherID)
    //                ->where('subject_teachers.subject_id', $subjectID)
    //                ->update(['grade' => $validated['grade']]);
    //        }
    //        return response()->json([
    //            'message' => "Student Subject Grade UPDATE successfully!",
    //        ]);
    //    }

    public function updateGrades(Request $request)
    {
        $validated = $request->validate([
            'students' => 'array|required',
            'students.*' => 'array|required',
            'students.*.grade' => 'numeric|min:' . Base::MIN_STUDENT_GRADE . "|max:" . Base::MAX_STUDENT_GRADE,
            'students.*.student_id' => 'numeric|exists:students,id',
            'subject_id' => "required|numeric|exists:subject_teachers,id",
            'resubmissions.*.resubmission' => 'nullable|numeric|min:' . Base::MIN_STUDENT_GRADE . "|max:" . Base::MAX_STUDENT_GRADE,
            'resubmissions.*.student_id' => 'numeric|exists:students,id',
        ]);

        foreach ($validated['students'] as $student) {
            $isApproved = $this->isGradeApproved($student, $validated);
            if (!$isApproved) :
                /*Note* If ever student will have two subject, fix this. (Fixed Already?)*/
                StudentSubject::join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
                    ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
                    ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
                    ->where('semesters.status', 1)
                    ->where('student_subjects.status', 0)
                    ->where('subject_teachers.id', $validated['subject_id'])
                    ->where('student_id', $student['student_id'])
                    ->update([
                        'grade' => $student['grade']
                    ]);
            endif;
        }

        foreach ($validated['resubmissions'] as $resubmission) {
            $isApproved = $this->isGradeApproved($resubmission, $validated);

            if ($isApproved)
                $exist = Resubmission::where('student_subject_id', $isApproved->student_subject_id)->exists();
            else
                $exist = false;

            if (
                $isApproved &&
                !is_null($resubmission['resubmission']) &&
                !$exist
            ) {
                Resubmission::create([
                    'student_subject_id' => $isApproved->student_subject_id,
                    'grade' => $resubmission['resubmission']
                ]);
            } elseif ($exist && is_null($resubmission['resubmission'])) {
                Resubmission::where(['student_subject_id' => $isApproved->student_subject_id])->delete();
            } elseif ($exist && !is_null($resubmission['resubmission'])) {
                Resubmission::where(['student_subject_id' => $isApproved->student_subject_id])
                    ->update(['grade' => $resubmission['resubmission']]);
            }
        }

        return response()->json([
            'message' => "Grades has been submitted for approval.",
        ]);
    }

    private function isGradeApproved($student, $validated)
    {
        return StudentSubject::where('student_id', $student['student_id'])
            ->join('subject_teachers', 'student_subjects.subject_teacher_id', 'subject_teachers.id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('semesters', 'student_subjects.semester_id', 'semesters.id')
            ->select('student_subjects.id as student_subject_id') //for optimize, select one column only
            ->where('semesters.status', 1)
            ->where('student_subjects.status', 1)
            ->where('subject_teachers.id', $validated['subject_id'])
            ->first();
    }
}
