<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TeacherAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('teacher.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim((string) $request->input('login'));

        $teacher = Teacher::query()
            ->active()
            ->where(function ($query) use ($login) {
                $query->whereRaw('LOWER(email) = ?', [mb_strtolower($login)])
                    ->orWhereRaw('LOWER(employee_id) = ?', [mb_strtolower($login)]);
            })
            ->first();

        // Password must match the stored password (initially 123456; changed via
        // "Forgot password"). No permanent default fallback.
        if (! $teacher || ! $teacher->portal_password || ! Hash::check($request->password, $teacher->portal_password)) {
            return back()->withErrors([
                'login' => 'Employee ID / email or password is incorrect.',
            ])->withInput();
        }

        Auth::guard('teacher')->login($teacher, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('teacher.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login')->with('success', 'You have been logged out.');
    }

    // ─── Forgot password (email OTP) ─────────────────────────────────────────

    public function showForgot()
    {
        return view('teacher.auth.forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['login' => 'required|string']);
        $login = trim((string) $request->input('login'));

        $teacher = Teacher::query()->active()
            ->where(function ($query) use ($login) {
                $query->whereRaw('LOWER(email) = ?', [mb_strtolower($login)])
                    ->orWhereRaw('LOWER(employee_id) = ?', [mb_strtolower($login)]);
            })
            ->first();

        if (! $teacher || empty($teacher->email)) {
            return back()->withErrors([
                'login' => $teacher && empty($teacher->email)
                    ? 'No email is on file for this teacher. Please contact the college office.'
                    : 'No active teacher found with that Employee ID or email.',
            ])->withInput();
        }

        $otp = (string) random_int(100000, 999999);
        Cache::put('portal_otp:teacher:' . $teacher->id, Hash::make($otp), now()->addMinutes(15));
        $request->session()->put('otp_teacher_id', $teacher->id);

        $this->mailOtp($teacher->email, $teacher->name, $otp);

        return redirect()->route('teacher.password.reset')
            ->with('success', 'A 6-digit code has been sent to your email (' . $this->maskEmail($teacher->email) . '). It expires in 15 minutes.');
    }

    public function showReset(Request $request)
    {
        if (! $request->session()->has('otp_teacher_id')) {
            return redirect()->route('teacher.password.request');
        }
        return view('teacher.auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp'      => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $teacherId = $request->session()->get('otp_teacher_id');
        $key = 'portal_otp:teacher:' . $teacherId;
        $hash = $teacherId ? Cache::get($key) : null;

        if (! $hash || ! Hash::check(trim($request->otp), $hash)) {
            return back()->withErrors(['otp' => 'The code is invalid or has expired. Please request a new one.']);
        }

        $teacher = Teacher::find($teacherId);
        if (! $teacher) {
            return redirect()->route('teacher.password.request');
        }

        $teacher->portal_password = $request->password;
        $teacher->save();

        Cache::forget($key);
        $request->session()->forget('otp_teacher_id');

        return redirect()->route('teacher.login')->with('success', 'Password updated. Please log in with your new password.');
    }

    private function mailOtp(string $email, ?string $name, string $otp): void
    {
        try {
            Mail::raw(
                "Assalam-o-Alaikum " . ($name ?: 'Teacher') . ",\n\n"
                . "Your JDCA teacher portal password reset code is: {$otp}\n\n"
                . "This code expires in 15 minutes. If you did not request this, please ignore this email.\n\n"
                . "— Jinnah Degree College Astore",
                fn ($m) => $m->to($email)->subject('JDCA Portal — Password Reset Code')
            );
        } catch (\Throwable) {
            // Mail failures shouldn't break the flow; the code is still stored.
        }
    }

    private function maskEmail(string $email): string
    {
        [$user, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $maskedUser = mb_substr($user, 0, 2) . str_repeat('*', max(1, mb_strlen($user) - 2));
        return $maskedUser . '@' . $domain;
    }
}
