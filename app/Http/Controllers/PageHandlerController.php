<?php

namespace App\Http\Controllers;

use App\Base;
use Illuminate\Http\Request;

class PageHandlerController extends Controller
{

    public function handle()
    {
        $userRole = auth()->user()->role_id ?? null;

        if($userRole == Base::REGISTRAR_ROLE_ID){
            return redirect()->route('registrar.index');
        }
        if($userRole == Base::TEACHER_ROLE_ID){
            return redirect()->route('teacher.index');
        }
        if($userRole == Base::STUDENT_ROLE_ID){
            return redirect()->route('student.announcement');
        }

//        abort(404);
        return redirect()->route('login');
    }
}
