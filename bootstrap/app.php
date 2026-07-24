<?php

use App\Support\ActivityLogWriter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust the platform proxy (Railway/Vercel/etc.) so Laravel detects
        // the original HTTPS scheme and handles secure session/CSRF cookies.
        $middleware->trustProxies(at: '*', headers:
            Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB
        );

        $middleware->redirectGuestsTo(fn () => url('/admin/login'));
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'auth.student' => \App\Http\Middleware\StudentAuthenticated::class,
            'auth.teacher' => \App\Http\Middleware\TeacherAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A stale CSRF token (419) should not show a scary "Page Expired" page —
        // send the user back to the form with a friendly message so they retry.
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('error', 'Your session expired. Please try again.');
        });

        $exceptions->report(function (\Throwable $exception): void {
            if (
                $exception instanceof ValidationException
                || $exception instanceof AuthenticationException
                || $exception instanceof AuthorizationException
                || $exception instanceof HttpResponseException
                || $exception instanceof TokenMismatchException
            ) {
                return;
            }

            if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() < 500) {
                return;
            }

            ActivityLogWriter::error(
                'exception',
                $exception->getMessage(),
                [
                    'class' => class_basename($exception),
                    'file' => basename($exception->getFile()),
                    'line' => $exception->getLine(),
                    'method' => request()?->method(),
                    'url' => request()?->fullUrl(),
                ]
            );
        });
    })->create();

// cPanel-style shared hosting: the web root (public_html) is a SEPARATE folder
// from the app root, not the app's own `public/` subfolder — so Laravel must be
// told the real served path, otherwise storage:link, asset(), and Vite would all
// generate paths inside the (unserved) `public/` folder instead. Only kicks in
// when that sibling folder actually exists, so local dev / other hosts are unaffected.
$cpanelPublicPath = dirname(__DIR__) . '/../public_html';
if (is_dir($cpanelPublicPath)) {
    $app->usePublicPath(realpath($cpanelPublicPath));
}

return $app;
