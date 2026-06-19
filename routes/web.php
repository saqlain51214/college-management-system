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
Route::get('/about',         [PublicController::class, 'about'])->name('about');
Route::get('/about/history', [PublicController::class, 'history'])->name('about.history');
Route::get('/about/mission', [PublicController::class, 'mission'])->name('about.mission');
Route::get('/programs',      [PublicController::class, 'programs'])->name('programs');
Route::get('/faculty',       [PublicController::class, 'faculty'])->name('faculty');
Route::get('/admissions',    [PublicController::class, 'admissions'])->name('admissions');
Route::get('/contact',       [PublicController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PublicController::class, 'contactSend'])->middleware('throttle:public-contact')->name('contact.send');
Route::post('/admissions/inquiry', [PublicController::class, 'admissionInquiry'])->middleware('throttle:public-admissions')->name('admissions.inquiry');
Route::get('/news',          [PublicController::class, 'news'])->name('news');
Route::get('/news/{slug}',   [PublicController::class, 'newsDetail'])->name('news.show');
Route::get('/events',        [PublicController::class, 'events'])->name('events');
Route::get('/notices',       [PublicController::class, 'notices'])->name('notices');
Route::get('/results',       [PublicController::class, 'results'])->name('results');
Route::get('/timetable',     [PublicController::class, 'timetable'])->name('timetable');
Route::get('/gallery',       [PublicController::class, 'gallery'])->name('gallery');

// ── Student Portal ──────────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login',  [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->middleware('throttle:student-login')->name('login.post');
    Route::post('/logout',[StudentAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.student')->group(function () {
        Route::get('/',                          [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/results',                   [StudentPortalController::class, 'results'])->name('results');
        Route::get('/fees',                      [StudentPortalController::class, 'fees'])->name('fees');
        Route::get('/fees/{payment}/challan',    [StudentPortalController::class, 'feeChallan'])->name('fees.challan');
        Route::get('/timetable',                 [StudentPortalController::class, 'timetable'])->name('timetable');
        Route::get('/notices',                   [StudentPortalController::class, 'notices'])->name('notices');
        Route::get('/profile',                   [StudentPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/password',         [StudentPortalController::class, 'updatePassword'])->name('profile.password');
    });
});

// ── Teacher Portal ──────────────────────────────────────────────────────
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [TeacherAuthController::class, 'login'])->middleware('throttle:teacher-login')->name('login.post');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.teacher')->group(function () {
        Route::get('/', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/timetable', [TeacherPortalController::class, 'timetable'])->name('timetable');
        Route::get('/materials', [TeacherPortalController::class, 'materials'])->name('materials');
        Route::get('/materials/create', [TeacherPortalController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [TeacherPortalController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{material}/edit', [TeacherPortalController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{material}', [TeacherPortalController::class, 'updateMaterial'])->name('materials.update');
        Route::get('/assignments', [TeacherPortalController::class, 'assignments'])->name('assignments');
        Route::get('/assignments/create', [TeacherPortalController::class, 'createAssignment'])->name('assignments.create');
        Route::post('/assignments', [TeacherPortalController::class, 'storeAssignment'])->name('assignments.store');
        Route::get('/assignments/{assignment}/edit', [TeacherPortalController::class, 'editAssignment'])->name('assignments.edit');
        Route::put('/assignments/{assignment}', [TeacherPortalController::class, 'updateAssignment'])->name('assignments.update');
        Route::get('/attendance', [TeacherPortalController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/create', [TeacherPortalController::class, 'createAttendance'])->name('attendance.create');
        Route::post('/attendance', [TeacherPortalController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/attendance/{session}/mark', [TeacherPortalController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/attendance/{session}/mark', [TeacherPortalController::class, 'saveAttendance'])->name('attendance.save');
        Route::get('/notices', [TeacherPortalController::class, 'notices'])->name('notices');
        Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/password', [TeacherPortalController::class, 'updatePassword'])->name('profile.password');
    });
});

// ── PDF downloads (authenticated) ──────────────────────────────────────
Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/challan/{payment}',     [PdfController::class, 'feeChallan'])->name('challan');
    Route::get('/transcript/{student}',  [PdfController::class, 'studentTranscript'])->name('transcript');
    Route::get('/attendance/{student}',  [PdfController::class, 'studentAttendance'])->name('attendance');
    Route::get('/exam-results/{exam}',   [PdfController::class, 'examResultSheet'])->name('exam-results');
});
