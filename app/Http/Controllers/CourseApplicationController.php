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
use Carbon\Carbon;

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
        $today = Carbon::now('Asia/Colombo'); // Adjust timezone to +0530
        $courses = Course::with(['batches' => function ($query) use ($today) {
            $query->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        }])->get()->filter(function ($course) {
            return $course->batches->isNotEmpty();
        })->values();
        return view('frontend.course-apply', compact('studyPrograms', 'courses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Dynamic validation based on study program and course
        $studyProgramId = $request->study_programme;
        $courseId = $request->course;
        $course = Course::with('studyProgram')->findOrFail($courseId);

        $validationRules = [
            'study_programme' => 'required|exists:study_programs,id',
            'course' => 'required|exists:courses,id',
            'ol_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'al_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'diploma_certificates.*' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'degree_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'transcript_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
            'other_certificates.*' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        ];

        // Define required documents based on study program/course type
        $requiredDocuments = [];
        switch ($studyProgramId) {
            case '1': // Bachelor's
                $requiredDocuments[] = 'ol_certificate';
                break;
            case '2': // Higher Diploma
                $requiredDocuments[] = 'al_certificate';
                $requiredDocuments[] = 'diploma_certificates';
                break;
            case '3': // Diploma
                $requiredDocuments[] = 'ol_certificate';
                break;
            case '4': // Postgraduate
                $requiredDocuments[] = 'degree_certificate';
                $requiredDocuments[] = 'transcript_certificate';
                break;
        }

        foreach ($requiredDocuments as $doc) {
            $validationRules[$doc] = ['required', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'];
        }

        $request->validate($validationRules);

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
        $uploadFields = ['ol_certificate', 'al_certificate', 'degree_certificate', 'transcript_certificate'];
        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                if ($request->file($field)->isValid()) {
                    $filename = "{$field}_{$request->file($field)->getClientOriginalName()}";
                    $path = $request->file($field)->storeAs($storagePath, $filename, 'public');
                    $courseApplication->$field = $path;
                    Log::info("{$field} uploaded", ['path' => $path]);
                } else {
                    Log::error("Invalid {$field} upload", ['file' => $request->file($field)]);
                    return redirect()->back()->with('error', "Invalid {$field} file.")->withInput();
                }
            }
        }

        if ($request->hasFile('diploma_certificates')) {
            $diplomaPaths = [];
            foreach ($request->file('diploma_certificates') as $index => $file) {
                if ($file->isValid()) {
                    $filename = "diploma_certificate_{$index}_{$file->getClientOriginalName()}";
                    $path = $file->storeAs($storagePath, $filename, 'public');
                    $diplomaPaths[] = $path;
                    Log::info('Diploma certificate uploaded', ['path' => $path]);
                } else {
                    Log::error('Invalid diploma certificate upload', ['file' => $file]);
                    return redirect()->back()->with('error', 'Invalid diploma certificate file.')->withInput();
                }
            }
            $courseApplication->diploma_certificates = json_encode($diplomaPaths);
        }

        if ($request->hasFile('other_certificates')) {
            $otherPaths = [];
            foreach ($request->file('other_certificates') as $index => $file) {
                if ($file->isValid()) {
                    $filename = "other_certificate_{$index}_{$file->getClientOriginalName()}";
                    $path = $file->storeAs($storagePath, $filename, 'public');
                    $otherPaths[] = $path;
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
        } else {
            $studentId = $existingStudent->student_id;
        }

        Log::info('Course application submitted', [
            'user_id' => $user->id,
            'course_application_id' => $courseApplication->id,
            'student_id' => $studentId
        ]);

        return redirect()->route('profile.edit')->with('status', 'Course application submitted successfully!');
    }
}