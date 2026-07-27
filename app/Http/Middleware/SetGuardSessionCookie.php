<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin, student portal, and teacher portal are three independent login
 * systems that can all be open in the same browser at once. Laravel's login()
 * calls session()->regenerate() for security, which rotates the CSRF token —
 * shared across all three, that meant logging into one role invalidated a
 * stale login form for another role still open in the same tab (419 Page
 * Expired). Giving each area its own session cookie makes them fully
 * independent — regenerating one never disturbs another's token or session.
 *
 * Must run before StartSession so the changed config takes effect for this
 * request's session resolution.
 */
class SetGuardSessionCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $cookie = match (true) {
            str_starts_with($path, 'admin')  => 'jdca_admin_session',
            str_starts_with($path, 'portal') => 'jdca_student_session',
            str_starts_with($path, 'teacher') => 'jdca_teacher_session',
            default => config('session.cookie'),
        };

        config(['session.cookie' => $cookie]);

        return $next($request);
    }
}
