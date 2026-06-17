<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentPortalController extends Controller
{
    private function student()
    {
        return Auth::guard('student')->user()->load('academicProgram');
    }

    public function dashboard()
    {
        $student = $this->student();

        $feeStats = [
            'total_due'  => FeePayment::where('student_id', $student->id)->sum('amount_due'),
            'total_paid' => FeePayment::where('student_id', $student->id)->sum('amount_paid'),
            'overdue'    => FeePayment::where('student_id', $student->id)
                ->where('payment_status', 'overdue')->count(),
        ];
        $feeStats['balance'] = $feeStats['total_due'] - $feeStats['total_paid'];

        $recentResults = ExamResult::with(['exam.course'])
            ->where('student_id', $student->id)
            ->whereHas('exam', fn($q) => $q->where('results_published', true))
            ->latest()->limit(5)->get();

        $avgGpa = $recentResults->whereNotNull('grade_points')->avg('grade_points');

        $notices = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->where(fn($q) => $q->where('audience', 'all')->orWhere('audience', 'students')->orWhereNull('audience'))
            ->orderByDesc('publish_date')->limit(5)->get();

        return view('portal.dashboard', compact('student', 'feeStats', 'recentResults', 'avgGpa', 'notices'));
    }

    public function results()
    {
        $student = $this->student();

        $results = ExamResult::with(['exam.course', 'exam.academicProgram'])
            ->where('student_id', $student->id)
            ->whereHas('exam', fn($q) => $q->where('results_published', true))
            ->orderByDesc('created_at')
            ->get();

        $grouped = $results->groupBy(fn($r) => $r->exam?->semester_number ?? 'Unknown');

        $cgpa = $results->whereNotNull('grade_points')->avg('grade_points');

        return view('portal.results', compact('student', 'results', 'grouped', 'cgpa'));
    }

    public function fees()
    {
        $student = $this->student();

        $payments = FeePayment::where('student_id', $student->id)
            ->orderByDesc('due_date')->get();

        $summary = [
            'total_due'  => $payments->sum('amount_due'),
            'total_paid' => $payments->sum('amount_paid'),
            'total_fine' => $payments->sum('fine_amount'),
        ];
        $summary['balance'] = $summary['total_due'] - $summary['total_paid'];

        return view('portal.fees', compact('student', 'payments', 'summary'));
    }

    public function timetable()
    {
        $student = $this->student();

        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];

        $slots = Timetable::with(['course','teacher'])
            ->forProgram($student->academic_program_id)
            ->forSemester($student->current_semester ?? 1)
            ->active()
            ->orderByRaw("CASE day_of_week WHEN 'monday' THEN 1 WHEN 'tuesday' THEN 2 WHEN 'wednesday' THEN 3 WHEN 'thursday' THEN 4 WHEN 'friday' THEN 5 WHEN 'saturday' THEN 6 ELSE 7 END")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('portal.timetable', compact('student', 'slots', 'days'));
    }

    public function feeChallan(FeePayment $payment)
    {
        $student = $this->student();

        if ($payment->student_id !== $student->id) {
            abort(403);
        }

        return app(PdfController::class)->feeChallan($payment);
    }

    public function notices()
    {
        $student = $this->student();

        $notices = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->where(fn($q) => $q->where('audience', 'all')->orWhere('audience', 'students')->orWhereNull('audience'))
            ->orderByDesc('publish_date')
            ->paginate(15);

        return view('portal.notices', compact('student', 'notices'));
    }

    public function profile()
    {
        $student = Auth::guard('student')->user()
            ->load(['academicProgram', 'department', 'academicYear']);

        return view('portal.profile', compact('student'));
    }

    public function updatePassword(Request $request)
    {
        $student = $this->student();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $valid = $student->portal_password
            ? Hash::check($request->current_password, $student->portal_password)
            : false;

        if (!$valid) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $student->update(['portal_password' => $request->password]);

        return back()->with('success', 'Password updated successfully.');
    }
}
