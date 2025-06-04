<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CourseApplication;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $applications = User::select(
            'users.id as user_id',
            \DB::raw('COALESCE(applications.full_name, CONCAT(users.name)) as full_name'),
            'applications.id as application_id',
            'applications.application_completed',
            'course_applications.study_programme',
            'course_applications.course'
        )
        ->where('users.type', 'student')
        ->leftJoin('applications', 'users.id', '=', 'applications.user_id')
        ->leftJoin('course_applications', 'users.id', '=', 'course_applications.user_id')
        ->paginate(5); // 5 items per page

        $currentPage = $applications->currentPage();
        $lastPage = $applications->lastPage();

        return view('frontend.admin', compact('applications', 'currentPage', 'lastPage'));
    }
}