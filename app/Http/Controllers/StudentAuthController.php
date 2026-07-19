<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('portal.dashboard');
        }
        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim($request->input('login'));

        // Log in with Registration Number (or Roll Number as a fallback identifier).
        $student = Student::where('is_active', true)
            ->where(fn ($q) => $q->where('registration_number', $identifier)->orWhere('roll_number', $identifier))
            ->first();

        if (! $student) {
            return back()->withErrors([
                'login' => 'No active student found with this registration/roll number. Please check it in the college office.',
            ])->withInput();
        }

        // Password must match the stored password (initially 123456; changed via
        // "Forgot password"). No permanent default fallback.
        if (! $student->portal_password || ! Hash::check($request->password, $student->portal_password)) {
            return back()->withErrors([
                'password' => 'The password is incorrect. If you forgot it, use "Forgot password?" to reset it.',
            ])->withInput();
        }

        Auth::guard('student')->login($student, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login')->with('success', 'You have been logged out.');
    }

    // ─── Forgot password (email OTP) ─────────────────────────────────────────

    public function showForgot()
    {
        return view('portal.auth.forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['login' => 'required|string']);
        $identifier = trim($request->input('login'));

        $student = Student::where('is_active', true)
            ->where(fn ($q) => $q->where('registration_number', $identifier)
                ->orWhere('roll_number', $identifier)
                ->orWhereRaw('LOWER(email) = ?', [mb_strtolower($identifier)]))
            ->first();

        if (! $student || empty($student->email)) {
            return back()->withErrors([
                'login' => $student && empty($student->email)
                    ? 'No email is on file for this student. Please contact the college office.'
                    : 'No active student found with that registration number or email.',
            ])->withInput();
        }

        $otp = (string) random_int(100000, 999999);
        Cache::put('portal_otp:student:' . $student->id, Hash::make($otp), now()->addMinutes(15));
        $request->session()->put('otp_student_id', $student->id);

        $this->mailOtp($student->email, $student->name, $otp);

        return redirect()->route('portal.password.reset')
            ->with('success', 'A 6-digit code has been sent to your email (' . $this->maskEmail($student->email) . '). It expires in 15 minutes.');
    }

    public function showReset(Request $request)
    {
        if (! $request->session()->has('otp_student_id')) {
            return redirect()->route('portal.password.request');
        }
        return view('portal.auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp'      => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $studentId = $request->session()->get('otp_student_id');
        $key = 'portal_otp:student:' . $studentId;
        $hash = $studentId ? Cache::get($key) : null;

        if (! $hash || ! Hash::check(trim($request->otp), $hash)) {
            return back()->withErrors(['otp' => 'The code is invalid or has expired. Please request a new one.']);
        }

        $student = Student::find($studentId);
        if (! $student) {
            return redirect()->route('portal.password.request');
        }

        $student->portal_password = $request->password;
        $student->save();

        Cache::forget($key);
        $request->session()->forget('otp_student_id');

        return redirect()->route('portal.login')->with('success', 'Password updated. Please log in with your new password.');
    }

    private function mailOtp(string $email, ?string $name, string $otp): void
    {
        try {
            Mail::raw(
                "Assalam-o-Alaikum " . ($name ?: 'Student') . ",\n\n"
                . "Your JDCA student portal password reset code is: {$otp}\n\n"
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
