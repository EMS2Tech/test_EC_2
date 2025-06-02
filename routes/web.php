<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
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
        $user = Auth::user();
        $intendedRoute = match ($user->type) {
            'admin' => route('admin.dashboard'),
            'user' => route('user.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('user.dashboard'),
        };
        Redirect::setIntendedUrl($intendedRoute);
        return view('auth.lock-screen');
    })->name('lock-screen');

    Route::get('/admin/dashboard', function () {
        return view('frontend.admin');
    })->name('admin.dashboard');

    Route::get('/user/dashboard', function () {
        return view('frontend.application');
    })->name('user.dashboard');

    Route::get('/manager/dashboard', function () {
        return view('dashboards.manager');
    })->name('manager.dashboard');

    Route::get('/application', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
});

require __DIR__.'/auth.php';

Route::get('/course', [App\Http\Controllers\CourseApplicationController::class, 'create'])->middleware(['auth'])->name('course.apply');
Route::post('/course', [App\Http\Controllers\CourseApplicationController::class, 'store'])->middleware(['auth'])->name('course.store');