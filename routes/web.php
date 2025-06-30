<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\CourseController;
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
            'user' => route('user.dashboard'),
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

    Route::get('/user/dashboard', function () {
        return view('frontend.application');
    })->name('user.dashboard')->middleware(RestrictType::class . ':user');

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

    Route::middleware(['auth', RestrictType::class . ':admin'])->group(function () {
    Route::get('/add-studyprogram', [StudyProgramController::class, 'index'])->name('admin.add-studyprogram');
    Route::post('/study-programs', [StudyProgramController::class, 'store'])->name('study-programs.store');});

    Route::middleware(['auth', RestrictType::class . ':admin'])->group(function () {
    Route::get('/add-course', [CourseController::class, 'index'])->name('admin.add-course');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store'); });
});

require __DIR__.'/auth.php';

Route::get('/course', [App\Http\Controllers\CourseApplicationController::class, 'create'])->name('course.apply');
Route::post('/course', [App\Http\Controllers\CourseApplicationController::class, 'store'])->name('course.store');