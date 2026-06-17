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
            'roll_number' => 'required|string',
            'password'    => 'required|string',
        ]);

        $student = Student::where('roll_number', trim($request->roll_number))
            ->where('is_active', true)
            ->first();

        if (! $student) {
            return back()->withErrors([
                'roll_number' => 'No active student found with this roll number.',
            ])->withInput();
        }

        // Verify portal password
        $valid = $student->portal_password
            ? Hash::check($request->password, $student->portal_password)
            : false;

        if (! $valid) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
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
