<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherPortalController;
use Illuminate\Support\Facades\Route;

// ── Public website ──────────────────────────────────────────────────────
Route::get('/',              [PublicController::class, 'home'])->name('home');

// About Us
Route::get('/about',               [PublicController::class, 'about'])->name('about');
Route::get('/about/history',       [PublicController::class, 'aboutHistory'])->name('about.history');
Route::get('/about/mission',       [PublicController::class, 'aboutMission'])->name('about.mission');
Route::get('/about/message',       [PublicController::class, 'aboutMessage'])->name('about.message');
Route::get('/about/director',      [PublicController::class, 'aboutDirector'])->name('about.director');
Route::get('/about/principal',     [PublicController::class, 'aboutPrincipal'])->name('about.principal');

// Academics
Route::get('/programs',            [PublicController::class, 'programs'])->name('programs');
Route::get('/faculty',             [PublicController::class, 'faculty'])->name('faculty');
Route::get('/departments',         [PublicController::class, 'departments'])->name('departments');
Route::get('/departments/{slug}',  [PublicController::class, 'departmentDetail'])->name('departments.show');
Route::get('/gallery',             [PublicController::class, 'gallery'])->name('gallery');
Route::get('/campus-facilities',   [PublicController::class, 'campusFacilities'])->name('campus-facilities');
Route::get('/downloads',           [PublicController::class, 'downloads'])->name('downloads');
Route::get('/search',              [PublicController::class, 'search'])->name('search');

// Admissions
Route::get('/admissions',             [PublicController::class, 'admissions'])->name('admissions');
Route::post('/admissions/inquiry',    [PublicController::class, 'admissionInquiry'])->middleware('throttle:public-admissions')->name('admissions.inquiry');
Route::get('/admissions/procedure',   [PublicController::class, 'admissionProcedure'])->name('admissions.procedure');
Route::get('/admissions/fee-structure', [PublicController::class, 'feeStructurePublic'])->name('admissions.fee-structure');
Route::get('/admissions/semester-rules', [PublicController::class, 'semesterRules'])->name('admissions.semester-rules');

// Scholarships
Route::get('/scholarships',           [PublicController::class, 'scholarships'])->name('scholarships');
Route::get('/scholarships/{type}',    [PublicController::class, 'scholarshipDetail'])->name('scholarships.show');

// Jobs
Route::get('/jobs',                         [PublicController::class, 'jobs'])->name('jobs');
Route::post('/jobs/apply', [PublicController::class, 'jobApply'])->middleware('throttle:public-contact')->name('jobs.apply');

// Contact
Route::get('/contact',       [PublicController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PublicController::class, 'contactSend'])->middleware('throttle:public-contact')->name('contact.send');

// News / Events / Notices
Route::get('/news',          [PublicController::class, 'news'])->name('news');
Route::get('/news/{slug}',   [PublicController::class, 'newsDetail'])->name('news.show');
Route::get('/events',        [PublicController::class, 'events'])->name('events');
Route::get('/notices',       [PublicController::class, 'notices'])->name('notices');

// ── Student Portal ──────────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login',   [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [StudentAuthController::class, 'login'])->middleware('throttle:student-login')->name('login.post');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.student')->group(function () {
        Route::get('/',                       [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/fees',                   [StudentPortalController::class, 'fees'])->name('fees');
        Route::get('/fees/{payment}/challan',         [StudentPortalController::class, 'feeChallan'])->name('fees.challan');
        Route::get('/fees/{payment}/challan/preview', [StudentPortalController::class, 'feeChallanPreview'])->name('fees.challan.preview');
        Route::post('/fees/{payment}/proof',          [StudentPortalController::class, 'uploadProof'])->name('fees.proof');
        Route::get('/notices',                [StudentPortalController::class, 'notices'])->name('notices');
        Route::get('/profile',                [StudentPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/password',      [StudentPortalController::class, 'updatePassword'])->name('profile.password');
    });
});

// ── Teacher Portal ──────────────────────────────────────────────────────
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/login',   [TeacherAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [TeacherAuthController::class, 'login'])->middleware('throttle:teacher-login')->name('login.post');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.teacher')->group(function () {
        Route::get('/',                   [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/notices',            [TeacherPortalController::class, 'notices'])->name('notices');
        Route::get('/profile',            [TeacherPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/password',  [TeacherPortalController::class, 'updatePassword'])->name('profile.password');
    });
});

// ── PDF downloads ───────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/challan/{payment}',         [PdfController::class, 'feeChallan'])->name('challan');
    Route::get('/challan/{payment}/preview', [PdfController::class, 'feeChallanPreview'])->name('challan.preview');
    Route::get('/transcript/{student}',      [PdfController::class, 'studentTranscript'])->name('transcript');
    Route::get('/attendance/{student}',      [PdfController::class, 'studentAttendance'])->name('attendance');
    Route::get('/exam/{exam}',               [PdfController::class, 'examResultSheet'])->name('exam');
});

// ── Admin: Fee Slip Template Preview ────────────────────────────────────
Route::get('/admin/fee-slip-preview/{template}', [App\Http\Controllers\PdfController::class, 'feeSlipPreview'])
    ->middleware(['auth'])
    ->name('admin.fee-slip.preview');
Route::get('/admin/fee-slip-preview/{template}/pdf', [App\Http\Controllers\PdfController::class, 'feeSlipPreviewPdf'])
    ->middleware(['auth'])
    ->name('admin.fee-slip.preview.pdf');
