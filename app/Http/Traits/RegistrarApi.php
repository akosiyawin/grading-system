<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Http\Traits;

use App\Base;
use App\Http\Resources\ActiveSemesterResource;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\DesignatedSubjectResource;
use App\Http\Resources\SchoolYearResource;
use App\Http\Resources\StudentGradeResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\TeacherAssignedResource;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TitleOnlyResource;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait RegistrarApi
{

    public function courseIndex()
    {
        return CourseResource::collection(Course::all());
    }


    public function courseStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required',
            'department_id' => 'required|exists:departments,id'
        ]);

        $data = Course::create($validated);

        return response()->json([
            'message' => 'Course has been saved successfully!',
            'data' => CourseResource::make($data)
        ]);
    }

    public function departmentIndex(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'Departments GET successful',
            'data' => TitleOnlyResource::collection(Department::all())
        ]);
    }


    public function departmentStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'max:250']
        ]);

        Department::create($validated);

        return response()->json([
            'message' => 'Department has been saved successfully!',
        ]);
    }

    public function teacherStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'unique:users', 'max:250'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'middle_name' => ['nullable']
        ]);
        $validated['role_id'] = Base::TEACHER_ROLE_ID;
        $validated['password'] = \Hash::make(Base::TEACHER_DEFAULT_PASSWORD);
        $user = User::create($validated);
        $teacher = $user->teacher()->create();
        return response()->json([
            'message' => 'Teacher has been saved successfully!',
        ]);
    }

    public function teacherIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'rowsPerPage' => 'required|numeric',
            'page' => 'required|numeric',
            'search' => 'nullable|string'
        ]);
        $query = Teacher::join('users', 'teachers.user_id', 'users.id');
        $query->select([
            'username',
            'teachers.id',
            'user_id',

        ]);
        $columns = [
            'username',
            'first_name',
            'last_name',
        ];
        $search = $request->get('search') ?? '';
        $query->where(function ($q) use ($columns, $search) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%${search}%");
            }
        });

        if ($request->get('rowsPerPage') == 99) {
            $teachers = $query->get();
        } else {
            $teachers = $query->paginate($request->get('rowsPerPage'));
        }

        $resources = TeacherResource::collection($teachers);
        return response()->json([
            'message' => 'Teachers GET successful',
            'data' => $resources,
        ]);
    }


    public function yearStore(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|numeric|min:2020|unique:school_years|digits:4|max:' . Base::SCHOOL_YEAR_MAX
        ]);
        $year = SchoolYear::create($validated);
        return response()->json([
            'message' => 'School Year Store successful',
            'data' => SchoolYearResource::make($year)
        ]);
    }

    public function yearIndex()
    {
        return response()->json([
            'message' => 'School Year GET successful',
            'data' => SchoolYearResource::collection(SchoolYear::all())
        ]);
    }

    public function semesterUpdate(Semester $semester)
    {
        Semester::where(['status' => 1])->update(['status' => 0]);
        $semester->update(['status' => 1]);
        return response()->json([
            'message' => 'Semester PATCH successful',
        ]);
    }

    public function activeSemester()
    {
        $semester = Semester::where('status', '=', 1)->first();
        return response()->json([
            'message' => 'Active Semester GET successful',
            'data' => ActiveSemesterResource::make($semester)
        ]);
    }

    public function subjectIndex()
    {
        $subjects = Subject::join('departments','subjects.department_id','departments.id')
            ->select([
                'subjects.id as subject_id',
                'subjects.title',
                'subjects.code',
                'subjects.units',
                'departments.title as department'
            ])
            ->groupBy('subjects.id')
            ->get();
        return response()->json([
            'message' => 'Subjects GET successful',
            'data' => SubjectResource::collection($subjects)
        ]);
    }

    public function subjectStore(Request $request)
    {
        $validated = $request->validate([
            'title' => "required|string|max:250",
            'code' => "required|string|max:250",
            'lab' => "nullable|numeric",
            'lecture' => "nullable|numeric",
            'department_id' => "required|numeric|exists:departments,id",
        ]);
        $validated['units'] = $validated['lecture'] . ($validated['lab'] ? " (${validated['lab']})" : "");
        $subject = Subject::create($validated);

        return response()->json([
            'message' => 'Subject Saved successful',
            'data' => SubjectResource::make($subject)
        ]);
    }


    public function subjectDesignated()
    {
        $subjects = Subject::join('subject_teachers', 'subjects.id', 'subject_teachers.subject_id');
        $subjects->join('semesters', 'subject_teachers.semester_id', 'semesters.id');
        $subjects->join('teachers', 'subject_teachers.teacher_id', 'teachers.id');
        $subjects->join('users', 'teachers.user_id', 'users.id');
        $subjects->where('semesters.status',1);
        $subjects->select([
            'teachers.id as teacher_id',
            'subjects.code',
            'subjects.id as subject_id',
            'subjects.title',
            'subjects.units',
            'users.id as user_id',
            'users.first_name',
            'users.last_name',
        ]);
        $subjects = $subjects->get();

        return response()->json([
            'message' => 'Designated Subjects GET successful',
            'data' => DesignatedSubjectResource::collection($subjects)
        ]);
    }

    // Teachers for assigning subjects
    public function teacherAssigned(Subject $subject)
    {
        $query = Teacher::join('subject_teachers', 'teachers.id', 'subject_teachers.teacher_id')
        ->join('semesters', 'subject_teachers.semester_id', 'semesters.id');;
        $query->where('semesters.status',1);
        $query->where('subject_id', $subject->id);
        $query->select([
            'teachers.id',
        ]);

        $assignedTeachers = array_map(function ($item) {
            return $item['id'];
        }, $query->get()->toArray());

        $teachers = Teacher::whereNotIn('teachers.id', $assignedTeachers);
        $teachers->join('users', 'teachers.user_id', 'users.id');
        $teachers->select([
            'teachers.id',
            'first_name',
            'last_name'
        ]);
        $teachers = TeacherAssignedResource::collection($teachers->get());
        return response()->json([
            'message' => 'Teachers GET successful',
            'data' => $teachers
        ]);
    }

    private function currentSemester()
    {
        return Semester::where('status', '=', 1)->first();
    }

    public function designateSubject(Teacher $teacher, Subject $subject)
    {
        $teacher = SubjectTeacher::create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'semester_id' => Semester::where('status',1)->pluck('id')[0]
        ]);
        return response()->json([
            'message' => 'Teacher Assigned to Subject successful',
            'data' => $teacher
        ]);
    }

    public function revokeSubject($teacher, $subject)
    {
        $students = StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
        ->join('semesters','subject_teachers.semester_id','semesters.id')
        ->where('semesters.status',1)
        ->where(['subject_teachers.subject_id' => $subject, 'subject_teachers.teacher_id' => $teacher])->first();
        if($students){
            return response()->json([
                'message' => 'Teacher has an existing students on selected subject.',
            ],400);
        }else{
            SubjectTeacher::where(['subject_id' => $subject, 'teacher_id' => $teacher])->delete();
            return response()->json([
                'message' => 'Teacher has been deleted successfully.',
            ],200);
        }
    }

    public function teacherInfo(Teacher $teacher)
    {
        $teachers = Teacher::join('subject_teachers', 'teachers.id', 'subject_teachers.teacher_id')
            ->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->join('users', 'teachers.user_id', 'users.id')
            ->where(['semesters.status' => 1, 'teacher_id' => $teacher->id])
            ->select([
                'subjects.title'
            ])
            ->get();

        return response()->json([
            'message' => 'Teachers subjects GET successful',
            'data' => $teachers,
            'name' => $teacher->user->fullName,
            'status' => $teacher->user->status,
            'user_id' => $teacher->user->id
        ]);
    }

    //We will delete the user profile too
    public function destroyTeacher(User $user)
    {
        if ($user->teacher->subjects && $user->teacher->subjects->count() > 0) {
            return response()->json([
                'message' => 'This teacher has an existing subjects record.',
            ], 400);
        }
        if ($user->teacher->students && $user->teacher->students->count() > 0) {
            return response()->json([
                'message' => 'This teacher has an existing students record.',
            ], 400);
        }

        $user->teacher->delete();
        $user->delete();
        return response()->json([
            'message' => 'Teacher Deleted Successfully',
        ]);
    }

    public function updateUserStatus(User $user)
    {
        $user->update(['status' => !$user->status]);
        return response()->json([
            'message' => 'User status updated successfully',
        ]);
    }


    public function studentIndex(Request $request)
    {
        $request->validate([
            'rowsPerPage' => 'required'
        ]);

        $query = Student::join('users', 'students.user_id', 'users.id');
//        $query->join('student_teachers','students.id','student_teachers.student_id');
//        $query->join('semesters','student_teachers.semester_id','semesters.id');
//        $query->where('semesters.status',1);
        $query->select([
            'first_name',
            'middle_name',
            'last_name',
            'students.id as student_id',
            'users.id as user_id',
            'users.username',
            'users.status as user_status',
            'students.birthdate',
            'students.course_id'
        ]);
        if ($request->get('search')) {
            $query->where('first_name', 'like', "%{$request->search}%");
            $query->orWhere('middle_name', 'like', "%{$request->search}%");
            $query->orWhere('last_name', 'like', "%{$request->search}%");
            $query->orWhere('username', 'like', "%{$request->search}%");
        }
        if ($request->get('rowsPerPage') == 99) {
            $students = StudentResource::collection($query->get());
        } else {
            $students = StudentResource::collection($query->paginate($request->rowsPerPage));
        }

        return response()->json([
            'message' => 'Students GET Successful',
            'data' => $students
        ]);
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'course_id' => 'required|exists:courses,id',
            'birthdate' => 'required|date',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
        ]);
        $validated['role_id'] = Base::STUDENT_ROLE_ID;
        $validated['password'] = \Hash::make(Carbon::parse($validated['birthdate'])->format('Y-m-d'));
        $user = User::create($validated);

        $user->student()->create($validated);

        return response()->json([
            'message' => 'Student Stored Successfully',
        ]);
    }


    public function destroyStudent(User $user)
    {
        if ($user->student->subjects->count() > 0) {
            return response()->json([
                'message' => 'Unable To Delete User',
                'reason' => 'Due to this user has an existing records of subjects.'
            ], 400);
        }

        /*if ($user->student->teachers->count() > 0) {
            return response()->json([
                'message' => 'Unable To Delete User',
                'reason' => 'Due to this user has an existing relation records to teachers.'
            ], 400);
        }*/

        $user->student->delete();
        $user->delete();

        return response()->json([
            'message' => 'The student has been deleted successfully!',
        ]);

    }

    public function destroyCourse(Course $course)
    {
        if($course->students->count() > 0) {
            return response()->json([
                'message' => 'Unable To Delete Course',
                'reason' => 'Due to this course has an existing records of students.'
            ], 400);
        }
        $course->delete();
        return response()->json([
            'message' => 'Course has been deleted successfully!'
        ]);
    }

    public function destroyDepartment(Department $department)
    {
        if($department->courses->count() > 0){
            return response()->json([
                'message' => 'Unable To Delete Department',
                'reason' => 'Due to this department has an existing records of courses.'
            ], 400);
        }
        if($department->subjects->count() > 0){
            return response()->json([
                'message' => 'Unable To Delete Department',
                'reason' => 'Due to this department has an existing records of subjects.'
            ], 400);
        }

        $department->delete();
        return response()->json([
            'message' => 'Department has been deleted successfully!'
        ]);
    }

    public function approveGrade(Request $request,$subject)
    {
        // dd($subject);
        /*
         * select student_subjects.id as student_subject_id, student_subjects.student_id,
         * student_subjects.grade, student_subjects.semester_id,
            subject_teachers.teacher_id, semesters.status as sem_status
        from student_subjects
        inner join subject_teachers on student_subjects.subject_teacher_id = subject_teachers.id
        inner join semesters on student_subjects.semester_id = semesters.id
        WHERE semesters.status = 1 and subject_teachers.teacher_id = 3 and student_subjects.id = 274
         * */
        $validated = $request->validate([
            'student_id' => 'required|numeric|exists:students,id',
            'teacher' => 'required|numeric|exists:teachers,id'
        ]);

        StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
        ->join('semesters','student_subjects.semester_id','semesters.id')
        ->where('semesters.status',1)
        ->where('subject_teachers.id',$subject)
        // ->where('subject_teachers.teacher_id',$validated['teacher'])
        ->where('student_subjects.student_id',$validated['student_id'])
        ->update(['student_subjects.status' => DB::raw('!student_subjects.status')]);
        return response()->json([
            'message' => 'Subject status has been updated successfully!'
        ]);
    }

    public function approveAllGrade(Request $request,Subject $subject)
    {
        $validated = $request->validate([
           'students' => 'required|array',
            'students.*' => 'numeric|exists:students,id',
            'teacher' => 'required|numeric|exists:teachers,id'
        ]);

        StudentSubject::join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
            ->join('semesters','student_subjects.semester_id','semesters.id')
            ->where('semesters.status',1)
            ->where('subject_teachers.teacher_id',$validated['teacher'])
            ->whereIn('student_subjects.student_id',$validated['students'])
            ->update(['student_subjects.status' => 1]);

        return response()->json([
            'message' => 'Students Grade has been approved successfully!'
        ]);
    }

    public function teacherSubjects(Teacher $teacher)
    {
        $subjects = $teacher->subjects()->join('subjects', 'subject_teachers.subject_id', 'subjects.id')
            ->join('semesters', 'subject_teachers.semester_id', 'semesters.id')
            ->where('semesters.status', 1)
            ->select([
                'subjects.code',
                DB::raw('CONCAT(subjects.title," - ",subject_teachers.remarks) as title'),
                'subject_teachers.id as subject_id'
            ])
            ->get();

        return response()->json([
            'message' => "Teacher Subject Lists GET successfully!",
            'data' => $subjects
        ]);
    }

    public function subjectStudentsGrade(Request $request, $subject)
    {
        $validated = $request->validate([
            'rowsPerPage' => 'numeric|required',
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'teacher' => 'required|numeric|exists:teachers,id'
        ]);
/*         $subjectTeacher = SubjectTeacher::join('semesters','subject_teachers.semester_id','semesters.id')
            ->where('subject_teachers.id',$subject)
            ->where('semesters.status',1)
            // ->where('teacher_id',$validated['teacher'])
//            ->where('subject_id',$subject->id)
            ->select(['subject_teachers.id'])
            ->first(); */

        /*
         * SELECT student_subjects.id, student_subjects.student_id,
        subject_teachers.semester_id as subject_teacher_semester,
         subject_teachers.teacher_id, subject_teachers.subject_id,
        semesters.title, semesters.status
        FROM student_subjects inner join subject_teachers on student_subjects.subject_teacher_id = subject_teachers.id
        inner join semesters on student_subjects.semester_id = semesters.id
        WHERE teacher_id = 3 and semesters.status = 1*/

        $students =  Student::join('student_subjects','students.id','student_subjects.student_id')
            ->join('users','students.user_id','users.id')
            ->join('subject_teachers','student_subjects.subject_teacher_id','subject_teachers.id')
            ->join('semesters','student_subjects.semester_id','semesters.id')
            ->where('subject_teachers.id',$subject)
            ->where('semesters.status',1)
            // ->where('teacher_id',$validated['teacher'])
//            ->where('subject_teachers.subject_id',$subject->id)
//            ->where('subject_teachers.id',$subjectTeacher->id)
            ->select([
                'students.id',
                'users.id as user_id',
                'student_subjects.grade',
                'student_subjects.status'
            ]);
//            ->where('teacher_id', $validated['teacher'])
//            ->where('semesters.status', 1)
//            ->where('subject_teachers.id', $subjectTeacher->id);

        $columns = [
            'username',
            'first_name',
            'last_name',
            'middle_name',
        ];

        $search = $validated['search'] ?? '';
        $students->where(function ($q) use ($columns, $search) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%${search}%");
            }
        });
        /*Fix later searching*/
        $students->groupBy('students.id');
        if ($validated['rowsPerPage'] == 99) {
            $data = $students->get();
        } else {
            $data = $students->paginate($request->get('rowsPerPage'));
        }
        return response()->json([
            'message' => "Teacher Subject Lists GET successfully!",
            'data' => StudentGradeResource::collection($data)
        ]);
    }

    public function announcementStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Announcement::create($validated);
        return response()->json([
            'message' => "Announcement posted successfully!",
        ]);
    }

    /*Helper functions*/
    private function findCourse($courses, $split)
    {
        $data = array_filter($courses->toArray(), function ($course) use ($split) {
            if (trim(strtolower($course['title'])) === trim(strtolower($split[6]))) {
                return $course['id'];
            }
        });

        if (empty($data)) {
            die(json_encode([
                'message' => "Unable to process",
                'reason' => "There is no such course as {$split[6]} in your system. Please check for typo."
            ]));
        } else {
            return $data[array_keys($data)[0]]['id'];
        }
    }

    public function bulkStudents(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        $existing = new Collection();

        $courses = Course::all();
        $password = \Hash::make(Base::STUDENT_DEFAULT_PASSWORD);
        $data = array_map(function ($item) use ($courses, $password) {
            $split = explode(',', $item);
            return [
                'username' => $split[3],
                'first_name' => substr(trim($split[1]),0,strlen($split[1])-5),$split,
                'middle_name' => substr(trim($split[1],'"'),-3),
                'last_name' => trim($split[0],'"'),
                'course_id' => $this->findCourse($courses, $split),
                'birthdate' => Carbon::parse(trim($split[4],'"').','.trim($split[5],'"'))->format('Y-m-d'),
                'role_id' => Base::STUDENT_ROLE_ID,
                'password' => $password
            ];
        }, $request->get('data'));
        foreach ($data as $student) {
            $user = User::where('username', $student['username'])->first();
            if (!$user) {
                $newUser = User::create($student);
                $newUser->student()->create($student);
            }else{
                $existing->push($user->username);
            }
        }

        return response()->json([
            'message' => 'Students Stored Successfully',
            'exist' => $existing
        ]);
    }

    public function bulkSubjects(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        $data = array_map(function ($item) {
            $split = explode(',', $item);
            if(count($split) === 6){
                $split[1] = trim($split[1],'"') . ", " . trim($split[2],'"');
                $split[2] = $split[3];
                $split[3] = $split[4];
                $split[4] = $split[5];
            }elseif (count($split) > 6){
                $split[1] = trim($split[1],'"') . ", " . trim($split[2],'"'). ", " . trim($split[3],'"');
                $split[2] = $split[4];
                $split[3] = $split[5];
                $split[4] = $split[6];
            }elseif (count($split) > 7){
                dd($split);
            }

            $lab = $split[3] !== "" ? ' ('.$split[3].')' : '';
            return [
                'code' => $split[0],
                'title' => $split[1],
                'units' => $split[2] . $lab,
                'department_id' => $split[4],
            ];
        }, $request->get('data'));

        Subject::insert($data);

        return response()->json([
            'message' => 'Subjects Stored Successfully',
        ]);
    }


}
