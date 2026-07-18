<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // Log in with Registration Number (or Roll Number as a fallback).
        $student = Student::where('is_active', true)
            ->where(fn ($q) => $q->where('registration_number', $identifier)->orWhere('roll_number', $identifier))
            ->first();

        if (! $student) {
            return back()->withErrors([
                'login' => 'No active student found with this registration/roll number.',
            ])->withInput();
        }

        // If a portal password is set, verify it; otherwise accept the default (123456)
        // or the student's own registration/roll number.
        $valid = $student->portal_password
            ? Hash::check($request->password, $student->portal_password)
            : in_array($request->password, ['123456', $student->registration_number, $student->roll_number], true);

        if (! $valid) {
            return back()->withErrors([
                'password' => 'Incorrect password. The default password is 123456.',
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
}
