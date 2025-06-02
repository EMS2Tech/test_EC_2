<?php

namespace App\Http\Controllers;

use App\Models\CourseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CourseApplicationController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->isUser() && !($user->application_completed ?? false)) {
            return redirect()->route('application.start')->with('error', 'Please complete your application before registering for a course.');
        }
        return view('frontend.course-apply');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'study_programme' => 'required|in:bachelors,higher_diploma,diploma,postgraduate_diploma',
            'course' => 'required|string|max:255',
            'ol_certificate' => ['required_if:study_programme,diploma,bachelors', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'al_certificate' => ['required_if:study_programme,higher_diploma,bachelors', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'diploma_certificates.*' => ['required_if:study_programme,higher_diploma,bachelors', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'degree_certificate' => ['required_if:study_programme,postgraduate_diploma', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'transcript_certificate' => ['required_if:study_programme,postgraduate_diploma', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'other_certificates.*' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        ]);

        $courseApplication = new CourseApplication([
            'user_id' => $user->id,
            'study_programme' => $request->study_programme,
            'course' => $request->course,
        ]);

        // Handle file uploads
        if ($request->hasFile('ol_certificate')) {
            $courseApplication->ol_certificate = $request->file('ol_certificate')->store('course_applications/ol', 'public');
        }
        if ($request->hasFile('al_certificate')) {
            $courseApplication->al_certificate = $request->file('al_certificate')->store('course_applications/al', 'public');
        }
        if ($request->hasFile('diploma_certificates')) {
            $diplomaPaths = [];
            foreach ($request->file('diploma_certificates') as $file) {
                $diplomaPaths[] = $file->store('course_applications/diploma', 'public');
            }
            $courseApplication->diploma_certificates = $diplomaPaths;
        }
        if ($request->hasFile('degree_certificate')) {
            $courseApplication->degree_certificate = $request->file('degree_certificate')->store('course_applications/degree', 'public');
        }
        if ($request->hasFile('transcript_certificate')) {
            $courseApplication->transcript_certificate = $request->file('transcript_certificate')->store('course_applications/transcript', 'public');
        }
        if ($request->hasFile('other_certificates')) {
            $otherPaths = [];
            foreach ($request->file('other_certificates') as $file) {
                $otherPaths[] = $file->store('course_applications/other', 'public');
            }
            $courseApplication->other_certificates = $otherPaths;
        }

        $courseApplication->save();

        Log::info('Course application submitted', ['user_id' => $user->id, 'course_application_id' => $courseApplication->id]);

        return redirect()->route('profile.edit')->with('status', 'Course application submitted successfully!');
    }
}