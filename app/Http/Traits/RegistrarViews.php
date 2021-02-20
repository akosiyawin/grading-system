<?php


namespace App\Http\Traits;

use App\Http\Resources\CourseResource;
use App\Http\Resources\TeacherResource;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait RegistrarViews
{

    public function index(){
        // $query = User::join('teachers','users.id','teachers.user_id')->find(6);
        // dd($query->pivot);
        return view('registrar.index');
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