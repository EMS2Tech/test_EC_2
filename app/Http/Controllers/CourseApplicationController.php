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

        // Fetch existing application to determine folder name
        $application = Application::where('user_id', $user->id)->first();
        if (!$application) {
            return redirect()->route('application.start')->with('error', 'Please complete your application first.');
        }

        // Determine folder name based on existing application data
        $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
        $storagePath = "applications/{$folderName}";

        // Ensure the folder exists
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        $courseApplication = new CourseApplication([
            'user_id' => $user->id,
            'study_programme_id' => $request->study_programme,
            'course_id' => $request->course,
        ]);

        // Handle file uploads with logging
        if ($request->hasFile('ol_certificate')) {
            if ($request->file('ol_certificate')->isValid()) {
                $path = "{$storagePath}/ol_certificate_{$request->file('ol_certificate')->getClientOriginalName()}";
                $courseApplication->ol_certificate = $request->file('ol_certificate')->storeAs($storagePath, "ol_certificate_{$request->file('ol_certificate')->getClientOriginalName()}", 'public');
                Log::info('OL certificate uploaded', ['path' => $courseApplication->ol_certificate]);
            } else {
                Log::error('Invalid OL certificate upload', ['file' => $request->file('ol_certificate')]);
                return redirect()->back()->with('error', 'Invalid OL certificate file.')->withInput();
            }
        }
        if ($request->hasFile('al_certificate')) {
            if ($request->file('al_certificate')->isValid()) {
                $path = "{$storagePath}/al_certificate_{$request->file('al_certificate')->getClientOriginalName()}";
                $courseApplication->al_certificate = $request->file('al_certificate')->storeAs($storagePath, "al_certificate_{$request->file('al_certificate')->getClientOriginalName()}", 'public');
                Log::info('AL certificate uploaded', ['path' => $courseApplication->al_certificate]);
            } else {
                Log::error('Invalid AL certificate upload', ['file' => $request->file('al_certificate')]);
                return redirect()->back()->with('error', 'Invalid AL certificate file.')->withInput();
            }
        }
        if ($request->hasFile('diploma_certificates')) {
            $diplomaPaths = [];
            foreach ($request->file('diploma_certificates') as $index => $file) {
                if ($file->isValid()) {
                    $filename = "diploma_certificate_{$index}_{$file->getClientOriginalName()}";
                    $path = "{$storagePath}/{$filename}";
                    $diplomaPaths[] = $file->storeAs($storagePath, $filename, 'public');
                    Log::info('Diploma certificate uploaded', ['path' => $path]);
                } else {
                    Log::error('Invalid diploma certificate upload', ['file' => $file]);
                    return redirect()->back()->with('error', 'Invalid diploma certificate file.')->withInput();
                }
            }
            $courseApplication->diploma_certificates = json_encode($diplomaPaths);
        }
        if ($request->hasFile('degree_certificate')) {
            if ($request->file('degree_certificate')->isValid()) {
                $path = "{$storagePath}/degree_certificate_{$request->file('degree_certificate')->getClientOriginalName()}";
                $courseApplication->degree_certificate = $request->file('degree_certificate')->storeAs($storagePath, "degree_certificate_{$request->file('degree_certificate')->getClientOriginalName()}", 'public');
                Log::info('Degree certificate uploaded', ['path' => $courseApplication->degree_certificate]);
            } else {
                Log::error('Invalid degree certificate upload', ['file' => $request->file('degree_certificate')]);
                return redirect()->back()->with('error', 'Invalid degree certificate file.')->withInput();
            }
        }
        if ($request->hasFile('transcript_certificate')) {
            if ($request->file('transcript_certificate')->isValid()) {
                $path = "{$storagePath}/transcript_certificate_{$request->file('transcript_certificate')->getClientOriginalName()}";
                $courseApplication->transcript_certificate = $request->file('transcript_certificate')->storeAs($storagePath, "transcript_certificate_{$request->file('transcript_certificate')->getClientOriginalName()}", 'public');
                Log::info('Transcript certificate uploaded', ['path' => $courseApplication->transcript_certificate]);
            } else {
                Log::error('Invalid transcript certificate upload', ['file' => $request->file('transcript_certificate')]);
                return redirect()->back()->with('error', 'Invalid transcript certificate file.')->withInput();
            }
        }
        if ($request->hasFile('other_certificates')) {
            $otherPaths = [];
            foreach ($request->file('other_certificates') as $index => $file) {
                if ($file->isValid()) {
                    $filename = "other_certificate_{$index}_{$file->getClientOriginalName()}";
                    $path = "{$storagePath}/{$filename}";
                    $otherPaths[] = $file->storeAs($storagePath, $filename, 'public');
                    Log::info('Other certificate uploaded', ['path' => $path]);
                } else {
                    Log::error('Invalid other certificate upload', ['file' => $file]);
                    return redirect()->back()->with('error', 'Invalid other certificate file.')->withInput();
                }
            }
            $courseApplication->other_certificates = json_encode($otherPaths);
        }

        $courseApplication->save();

        // Check if student record already exists
        $existingStudent = Student::where('user_id', $user->id)->first();

        if (!$existingStudent) {
            // Generate student_id only if no student record exists
            $application = Application::where('user_id', $user->id)->first();
            $course = Course::find($request->course);
            $batch = $course->batches()->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
            $fullName = $application->full_name ?? 'Unknown';
            $shortName = $course->short_name;
            $batchNo = $batch ? $batch->batch_no : '01';
            $currentYear = date('Y');
            $applicationNo = $application->id;

            $studentId = "EC/{$shortName}/{$batchNo}/{$currentYear}/{$applicationNo}";
            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'full_name' => $fullName,
            ]);

            Log::info('Student ID created', ['user_id' => $user->id, 'student_id' => $studentId]);
        }

        Log::info('Course application submitted', ['user_id' => $user->id, 'course_application_id' => $courseApplication->id, 'student_id' => $existingStudent->student_id ?? $studentId ?? null]);

        return redirect()->route('profile.edit')->with('status', 'Course application submitted successfully!');
    }
}