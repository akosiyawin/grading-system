<?php

namespace App\Http\Middleware;

use App\Base;
use Closure;
use Illuminate\Http\Request;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->role_id != Base::TEACHER_ROLE_ID){
            abort(403);
        }
        return $next($request);
    }
}
