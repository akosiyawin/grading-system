<?php


namespace App\Http\Traits;

use App\Base;
use App\Http\Resources\CourseResource;
use App\Http\Resources\TeacherResource;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait RegistrarViews
{

    public function index()
    {
        $semester = Semester::where('status', 1)->first();
        $students = User::where('role_id', Base::STUDENT_ROLE_ID)->count();
        $teachers = User::where('role_id', Base::TEACHER_ROLE_ID)->count();
        $registrars = User::where('role_id', Base::REGISTRAR_ROLE_ID)->count();
        $subjects = Subject::count();
        $courses = Course::count();
        $departments = Department::count();
        $approvals = StudentSubject::where('status', 0)->where('semester_id', $semester->id)->count();
        $approved = StudentSubject::where('status', 1)->where('semester_id', $semester->id)->count();
        $submissions = StudentSubject::where('semester_id', $semester->id)->count();
        $suspended = User::where('status', 0)->count();
        $year = "School Year of {$semester->schoolYear->year} - {$semester->title}";
        return view('registrar.index', compact(
            'students',
            'teachers',
            'registrars',
            'subjects',
            'courses',
            'departments',
            'approvals',
            'approved',
            'submissions',
            'suspended',
            'year'
        ));
    }

    public function teacherView()
    {
        return view('registrar.teacher');
    }

    public function createSubject()
    {
        return view('registrar.subject');
    }

    public function yearSemesterView()
    {
        return view('registrar.yearSem');
    }

    public function studentView()
    {
        return view('registrar.student');
    }

    public function setup()
    {
        return view('registrar.setup');
    }

    public function announcementIndex()
    {
        return view('registrar.announcement');
    }
}