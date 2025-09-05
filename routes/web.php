<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SubjectController;
use App\Http\Middleware\RestrictType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/photograph', [ApplicationController::class, 'updatePhotograph'])->name('profile.photograph');

    Route::get('/lock-screen', function () {
        $user = Auth::user();
        $intendedRoute = Auth::check() ? match ($user->type) {
            'admin' => route('admin.dashboard'),
            'student' => Application::where('user_id', $user->id)->value('application_completed') ? route('profile.edit') : route('user.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('user.dashboard'),
        } : route('login');
        Redirect::setIntendedUrl($intendedRoute);
        return view('auth.lock-screen');
    })->name('lock-screen');

    // Apply middleware class directly
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware(RestrictType::class . ':admin');
    Route::get('/admin/applications', [AdminController::class, 'applications'])
        ->name('admin.applications')
        ->middleware(RestrictType::class . ':admin');

    Route::get('/student/application', function () {
        return view('frontend.application');
    })->name('user.dashboard')->middleware(RestrictType::class . ':student');

    Route::get('/manager/dashboard', function () {
        return view('dashboards.manager');
    })->name('manager.dashboard');

    Route::get('/application', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');

    Route::get('/payment', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');

    // Admin payment status update
    Route::post('/admin/payment/{userId}/status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payment.status')->middleware(RestrictType::class . ':admin');

    // View application details
    Route::get('/admin/application/{id}/view', [AdminController::class, 'viewApplicationDetails'])->name('admin.application.details')->middleware(RestrictType::class . ':admin');

    // Update application status
    Route::put('/admin/application/{id}/update-status', [AdminController::class, 'updateApplicationStatus'])->name('admin.application.update-status')->middleware(RestrictType::class . ':admin');

    Route::get('/course-apply', [CourseApplicationController::class, 'create'])->name('course-application.create');
    Route::post('/course-apply', [CourseApplicationController::class, 'store'])->name('course-application.store');
    Route::get('/get-courses/{studyProgramId}', [CourseApplicationController::class, 'getCourses']);

    // Admin pages route
    Route::middleware(['auth', RestrictType::class . ':admin'])->group(function () {
        Route::get('/add-studyprogram', [StudyProgramController::class, 'index'])->name('admin.add-studyprogram');
        Route::post('/study-programs', [StudyProgramController::class, 'store'])->name('study-programs.store');
        Route::get('/study-programs/{id}/edit', [StudyProgramController::class, 'edit'])->name('study-programs.edit');
        Route::delete('/study-programs/{id}', [StudyProgramController::class, 'destroy'])->name('study-programs.destroy');
        Route::patch('/study-programs/{id}', [StudyProgramController::class, 'update'])->name('study-programs.update');

        Route::get('/add-course', [CourseController::class, 'index'])->name('admin.add-course');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::patch('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');

        Route::get('/add-batch', [BatchController::class, 'index'])->name('admin.add-batch');
        Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
        Route::get('/batches/{id}/edit', [BatchController::class, 'edit'])->name('batches.edit');
        Route::delete('/batches/{id}', [BatchController::class, 'destroy'])->name('batches.destroy');
        Route::patch('/batches/{id}', [BatchController::class, 'update'])->name('batches.update');

        Route::get('/course-applications', [AdminController::class, 'courseApplications'])->name('admin.course.applications');
        Route::get('/admin/course/applications/export', [AdminController::class, 'export'])->name('admin.course.applications.export');

        Route::get('/add-subject', [SubjectController::class, 'index'])->name('admin.add-subject');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
        Route::patch('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    });
});

require __DIR__.'/auth.php';