<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('student')->check()) {
            return redirect()->route('portal.login')
                ->with('error', 'Please log in to access the student portal.');
        }
        return $next($request);
    }
}
