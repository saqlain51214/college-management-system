<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentPortalController;
use Illuminate\Support\Facades\Route;

// ── Public website ──────────────────────────────────────────────────────
Route::get('/',              [PublicController::class, 'home'])->name('home');
Route::get('/about',         [PublicController::class, 'about'])->name('about');
Route::get('/programs',      [PublicController::class, 'programs'])->name('programs');
Route::get('/faculty',       [PublicController::class, 'faculty'])->name('faculty');
Route::get('/admissions',    [PublicController::class, 'admissions'])->name('admissions');
Route::get('/contact',       [PublicController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PublicController::class, 'contactSend'])->name('contact.send');
Route::post('/admissions/inquiry', [PublicController::class, 'admissionInquiry'])->name('admissions.inquiry');
Route::get('/news',          [PublicController::class, 'news'])->name('news');
Route::get('/news/{slug}',   [PublicController::class, 'newsDetail'])->name('news.show');
Route::get('/notices',       [PublicController::class, 'notices'])->name('notices');
Route::get('/results',       [PublicController::class, 'results'])->name('results');

// ── Student Portal ──────────────────────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login',  [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.post');
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

// ── PDF downloads (authenticated) ──────────────────────────────────────
Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/challan/{payment}',     [PdfController::class, 'feeChallan'])->name('challan');
    Route::get('/transcript/{student}',  [PdfController::class, 'studentTranscript'])->name('transcript');
    Route::get('/attendance/{student}',  [PdfController::class, 'studentAttendance'])->name('attendance');
    Route::get('/exam-results/{exam}',   [PdfController::class, 'examResultSheet'])->name('exam-results');
});
