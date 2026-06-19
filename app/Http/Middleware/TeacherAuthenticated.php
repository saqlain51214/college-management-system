<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('teacher')->check()) {
            return redirect()->route('teacher.login')
                ->with('error', 'Please log in to access the teacher portal.');
        }

        return $next($request);
    }
}
