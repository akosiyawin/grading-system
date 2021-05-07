<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function assistance(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string'
        ]);

        if ($validated['password'] !== "east123") {
            return response()->json(['message' => 'Invalid Password!'], 400);
        }

        $users = \App\Models\Student::join('users', 'students.user_id', 'users.id')
            ->join('courses', 'students.course_id', 'courses.id')
            ->where('users.role_id', \App\Base::STUDENT_ROLE_ID)
            ->select([
                'students.id',
                'users.username as snum',
                'students.birthdate',
                DB::raw('CONCAT(users.first_name,", ",users.last_name," ",IFNULL(users.middle_name, "")) as name'),
            ])->get();

        return response()->json(['message' => 'Students GET Successful', 'data' => $users]);
    }
}
