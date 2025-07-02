<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BatchController;
use App\Http\Middleware\RestrictType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/photograph', [ApplicationController::class, 'updatePhotograph'])->name('profile.photograph');

    Route::get('/lock-screen', function () {
        $intendedRoute = Auth::check() ? match (Auth::user()->type) {
            'admin' => route('admin.dashboard'),
            'student' => route('user.dashboard'),
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

    // View application details (placeholder)
    Route::get('/admin/application/{id}/view', [AdminController::class, 'viewApplication'])->name('admin.application.view')->middleware(RestrictType::class . ':admin');

    // Admin pages route
    Route::middleware(['auth', RestrictType::class . ':admin'])->group(function () {

    Route::get('/add-studyprogram', [StudyProgramController::class, 'index'])->name('admin.add-studyprogram');
    Route::post('/study-programs', [StudyProgramController::class, 'store'])->name('study-programs.store');

    Route::get('/add-course', [CourseController::class, 'index'])->name('admin.add-course');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    Route::get('/add-batch', [BatchController::class, 'index'])->name('admin.add-batch');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');

    Route::get('/course-applications', [AdminController::class, 'courseApplications'])->name('admin.course.applications');
});

    

    Route::get('/course-apply', [CourseApplicationController::class, 'create'])->name('course-application.create');
    Route::post('/course-apply', [CourseApplicationController::class, 'store'])->name('course-application.store');
    Route::get('/get-courses/{studyProgramId}', [CourseApplicationController::class, 'getCourses']);
});

require __DIR__.'/auth.php';

