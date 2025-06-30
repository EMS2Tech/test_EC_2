<?php

namespace App\Http\Controllers;

use App\Models\CourseApplication;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Student;
use App\Models\User;
use App\Models\Application;
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
        $studyPrograms = StudyProgram::all();
        return view('frontend.course-apply', compact('studyPrograms'));
    }

    public function getCourses($studyProgramId)
    {
        $today = now();
        $courses = Course::whereHas('studyProgram', function ($query) use ($studyProgramId) {
            $query->where('id', $studyProgramId);
        })->with(['batches' => function ($query) use ($today) {
            $query->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        }])->get();

        Log::info('Fetched courses for studyProgramId: ' . $studyProgramId, ['courses' => $courses->toArray()]);

        $courseOptions = $courses->mapWithKeys(function ($course) {
            $batchInfo = $course->batches->isNotEmpty()
                ? $course->batches->map(function ($batch) {
                    return $batch->batch_no;
                })->join(', ')
                : 'No active batches';
            return [$course->id => "{$course->course_name} (Batch(s): {$batchInfo})"];
        })->all();

        Log::info('Course options for studyProgramId: ' . $studyProgramId, ['options' => $courseOptions]);

        return response()->json($courseOptions);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'study_programme' => 'required|exists:study_programs,id',
            'course' => 'required|exists:courses,id',
            'ol_certificate' => ['required_if:study_programme,1', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'al_certificate' => ['required_if:study_programme,2', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'diploma_certificates.*' => ['required_if:study_programme,2,1', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'degree_certificate' => ['required_if:study_programme,4', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'transcript_certificate' => ['required_if:study_programme,4', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'other_certificates.*' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        ]);

        $courseApplication = new CourseApplication([
            'user_id' => $user->id,
            'study_programme_id' => $request->study_programme,
            'course_id' => $request->course,
        ]);

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
            $courseApplication->diploma_certificates = json_encode($diplomaPaths);
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
            $courseApplication->other_certificates = json_encode($otherPaths);
        }

        $courseApplication->save();

        // Generate student_id
        $application = Application::where('user_id', $user->id)->first();
        $course = Course::find($request->course);
        $batch = $course->batches()->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        $fullName = $application->full_name ?? 'Unknown'; // Fetch full_name from applications table
        $shortName = $course->short_name;
        $batchNo = $batch ? $batch->batch_no : '01'; // Default to '01' if no active batch
        $currentYear = date('Y');
        $applicationNo = $application->id;

        $studentId = "EC/{$shortName}/{$batchNo}/{$currentYear}/{$applicationNo}";
        Student::create([
            'student_id' => $studentId,
            'full_name' => $fullName,
        ]);

        Log::info('Course application submitted', ['user_id' => $user->id, 'course_application_id' => $courseApplication->id, 'student_id' => $studentId]);

        return redirect()->route('profile.edit')->with('status', 'Course application submitted successfully!');
    }
}