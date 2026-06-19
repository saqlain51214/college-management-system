<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'login' => 'required|string',
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

        if (! $teacher) {
            return back()->withErrors([
                'login' => 'No active teacher found with this email or employee ID.',
            ])->withInput();
        }

        $valid = $teacher->portal_password
            ? Hash::check($request->password, $teacher->portal_password)
            : ($request->password === $teacher->employee_id);

        if (! $valid) {
            return back()->withErrors([
                'password' => 'Incorrect password. Your default password is your employee ID.',
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
}
