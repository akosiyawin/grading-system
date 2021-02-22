<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventSuspendedMiddleware
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
        if($user->status == 0){
            auth()->logout();
            return redirect()->back()->with('message','Your account has been suspended! Please Contact the Admin.');
        }
        return $next($request);
    }
}
