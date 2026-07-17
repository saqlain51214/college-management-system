<?php

namespace App\Http\Controllers;

use App\Mail\StudentPortalPasswordChangedMail;
use App\Models\Announcement;
use App\Models\FeePayment;
use App\Models\FeeSlipTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        $notices = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->where(fn($q) => $q->where('audience', 'all')->orWhere('audience', 'students')->orWhereNull('audience'))
            ->orderByDesc('publish_date')->limit(5)->get();

        return view('portal.dashboard', compact('student', 'feeStats', 'notices'));
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

    public function feeChallan(FeePayment $payment)
    {
        $student = $this->student();

        if ($payment->student_id !== $student->id) {
            abort(403);
        }

        return app(PdfController::class)->feeChallan($payment);
    }

    public function feeChallanPreview(FeePayment $payment)
    {
        $student = $this->student();

        if ($payment->student_id !== $student->id) {
            abort(403);
        }

        $payment->load(['student.academicProgram', 'student.academicYear', 'feeStructure', 'academicYear']);
        $template = FeeSlipTemplate::active();
        return view('portal.fee-challan-preview', compact('payment', 'template'));
    }

    public function uploadProof(Request $request, FeePayment $payment)
    {
        $student = $this->student();
        if ($payment->student_id !== $student->id) abort(403);

        $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($payment->payment_proof_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->payment_proof_path);
        }

        $path = $request->file('proof')->store('payment-proofs', 'public');
        $payment->update([
            'payment_proof_path' => $path,
            'proof_uploaded_at'  => now(),
        ]);

        return back()->with('proof_uploaded', 'Payment proof uploaded. Admin will verify and mark as paid shortly.');
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
            : ($request->current_password === $student->roll_number);

        if (!$valid) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $student->update(['portal_password' => $request->password]);

        if (filled($student->email)) {
            Mail::to($student->email)->queue(new StudentPortalPasswordChangedMail($student->fresh()));
        }

        return back()->with('success', 'Password updated successfully.');
    }
}
