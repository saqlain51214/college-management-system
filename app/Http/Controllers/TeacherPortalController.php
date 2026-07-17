<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherPortalController extends Controller
{
    private function teacher(): Teacher
    {
        /** @var Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        return $teacher->load('department');
    }

    public function dashboard()
    {
        $teacher = $this->teacher();

        $notices = $this->teacherAnnouncements()
            ->orderByDesc('publish_date')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('teacher', 'notices'));
    }

    public function notices()
    {
        $teacher = $this->teacher();

        $notices = $this->teacherAnnouncements()
            ->orderByDesc('publish_date')
            ->paginate(15);

        return view('teacher.notices', compact('teacher', 'notices'));
    }

    public function profile()
    {
        $teacher = $this->teacher();

        return view('teacher.profile', compact('teacher'));
    }

    public function updatePassword(Request $request)
    {
        $teacher = $this->teacher();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $valid = $teacher->portal_password
            ? Hash::check($request->current_password, $teacher->portal_password)
            : ($request->current_password === $teacher->employee_id);

        if (! $valid) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $teacher->update(['portal_password' => $request->password]);

        return back()->with('success', 'Password updated successfully.');
    }

    private function teacherAnnouncements()
    {
        return Announcement::query()
            ->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->where(fn ($q) => $q->where('audience', 'all')->orWhere('audience', 'teachers')->orWhereNull('audience'));
    }
}
