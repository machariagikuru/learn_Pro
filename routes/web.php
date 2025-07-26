<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\StudentDashboardController;

// ✅ StrandNotes-style content browsing route
Route::get('/subjects/{subject}/{strand}/{subStrand}', [NoteController::class, 'showSubStrandNotes'])
    ->name('notes.browse');

// ✅ Homepage and Dashboard
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Authenticated user profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Subjects system (formerly Courses)
Route::resource('subjects', SubjectController::class);
Route::post('/subjects/{subject}/enroll', [StudentSubjectController::class, 'enroll'])->name('subjects.enroll');

// ✅ Assignments
Route::resource('assignments', AssignmentController::class);
Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');

// ✅ Certificate generation
Route::get('/certificates/{subject}', [CertificateController::class, 'generate'])->name('certificates.generate');

// ✅ Student dashboard
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

// ✅ Notes management
Route::get('/subjects/{subject}/strands/{strand}/sub-strands/{subStrand}/notes/{note}', [NoteController::class, 'show'])->name('notes.view');

require __DIR__.'/auth.php';
