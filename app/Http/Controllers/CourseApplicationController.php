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
    $studyPrograms = StudyProgram::with('courses')->get();
    $today = Carbon::now('Asia/Colombo');
    $courses = Course::with(['batches' => function ($query) use ($today) {
        $query->where('start_date', '<=', $today)
              ->where('end_date', '>=', $today);
    }])->get()->filter(function ($course) {
        return $course->batches->isNotEmpty();
    })->values();

    // Flatten courses with their active batches
    $courseBatches = [];
    foreach ($courses as $course) {
        foreach ($course->batches as $batch) {
            $courseBatches[] = [
                'course_id' => $course->id,
                'course_name' => $course->course_name,
                'batch_id' => $batch->id,
                'batch_no' => $batch->batch_no,
                'program_id' => $course->program_id,
            ];
        }
    }

    return view('frontend.course-apply', compact('studyPrograms', 'courseBatches'));
}

    public function store(Request $request)
{
    $user = Auth::user();

    $studyProgramId = $request->study_programme;
    $courseBatchId = $request->course; // e.g., "1_1"
    list($courseId, $batchId) = explode('_', $courseBatchId);
    $course = Course::with('studyProgram')->findOrFail($courseId);
    $requiredDocuments = $course->studyProgram->required_documents ?? [];

    $validationRules = [
        'study_programme' => 'required|exists:study_programs,id',
        'course' => 'required|regex:/^\d+_\d+$/', // Validate the format "course_id_batch_id"
        'ol_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        'al_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        'diploma_certificates' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'], // Single file
        'degree_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        'transcript_certificate' => ['sometimes', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'],
        'other_certificates' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'], // Single file
    ];

    foreach ($requiredDocuments as $doc) {
        $validationRules[$doc] = ['required', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:4096'];
    }

    $request->validate($validationRules);

    $application = Application::where('user_id', $user->id)->first();
    if (!$application) {
        return redirect()->route('application.start')->with('error', 'Please complete your application first.');
    }

    $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
    $storagePath = "applications/{$folderName}";

    if (!Storage::disk('public')->exists($storagePath)) {
        Storage::disk('public')->makeDirectory($storagePath);
    }

    $courseApplication = new CourseApplication([
        'user_id' => $user->id,
        'study_programme_id' => $request->study_programme,
        'course_id' => $courseId,
        'batch_id' => $batchId, // Use the extracted batch_id
        'status' => 'Pending',
    ]);

    $uploadFields = ['ol_certificate', 'al_certificate', 'degree_certificate', 'transcript_certificate', 'diploma_certificates', 'other_certificates'];
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

    $courseApplication->save();

    Log::info('Course application submitted', [
        'user_id' => $user->id,
        'course_application_id' => $courseApplication->id,
    ]);

    return redirect()->route('profile.edit')->with('status', 'Course application submitted successfully!');
}

    public function view($id)
    {
        $courseApplication = CourseApplication::with(['user.application', 'studyProgram', 'course.batches'])->findOrFail($id);
        return view('frontend.courseapplication-view', compact('courseApplication'));
    }

    public function updateStatus(Request $request, $id)
{
    $courseApplication = CourseApplication::findOrFail($id);
    $status = $request->input('status');
    $reason = $request->input('reason');

    $courseApplication->status = $status;
    if ($status === 'Rejected' && $reason) {
        $courseApplication->rejection_reason = $reason;
    } elseif ($status === 'Rejected' && !$reason) {
        return redirect()->back()->with('error', 'Rejection reason is required when rejecting an application.');
    } elseif ($status === 'Pending') {
        // If status is set to Pending manually by admin (e.g., for review), retain rejection reason
        if ($courseApplication->rejection_reason) {
            $courseApplication->rejection_reason = $courseApplication->rejection_reason; // Keep existing reason
        }
    } else {
        $courseApplication->rejection_reason = null; // Clear reason for Approved
    }

    // Set the admin who updated the status
    $courseApplication->updated_by = Auth::id();

    $courseApplication->save();

    if ($status === 'Approved') {
        $userId = $courseApplication->user_id;
        $existingStudent = Student::where('user_id', $userId)->first();

        if (!$existingStudent) {
            $application = Application::where('user_id', $userId)->first();
            $course = Course::find($courseApplication->course_id);
            $batch = $course->batches()->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
            $fullName = $application->full_name ?? 'Unknown';
            $shortName = $course->short_name;
            $batchNo = $batch ? $batch->batch_no : '01';
            $currentYear = date('Y');
            $applicationNo = $application->id;

            $studentId = "EC/{$shortName}/{$batchNo}/{$currentYear}/{$applicationNo}";
            Student::create([
                'user_id' => $userId,
                'student_id' => $studentId,
                'full_name' => $fullName,
            ]);

            Log::info('Student ID created on first approval', ['user_id' => $userId, 'student_id' => $studentId]);
        } else {
            Log::info('Student ID already exists, no new student ID created', ['user_id' => $userId, 'existing_student_id' => $existingStudent->student_id]);
        }
    }

    Log::info('Course application status updated', [
        'course_application_id' => $courseApplication->id,
        'user_id' => $courseApplication->user_id,
        'status' => $courseApplication->status,
        'rejection_reason' => $courseApplication->rejection_reason,
        'updated_by' => $courseApplication->updated_by,
    ]);

    return redirect()->route('admin.course.applications')->with('status', 'Application status updated successfully!');
}

    public function userUpdateDocuments(Request $request)
{
    $user = Auth::user();
    $courseApplications = CourseApplication::where('user_id', $user->id)->where('status', 'Rejected')->get();

    if ($courseApplications->isEmpty()) {
        return redirect()->back()->with('error', 'No rejected applications found to update.');
    }

    $application = Application::where('user_id', $user->id)->first();
    if (!$application) {
        return redirect()->route('application.start')->with('error', 'Please complete your application first.');
    }

    $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
    $storagePath = "applications/{$folderName}";

    if (!Storage::disk('public')->exists($storagePath)) {
        Storage::disk('public')->makeDirectory($storagePath);
    }

    $validatedData = $request->validate([
        'ol_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'al_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'degree_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'transcript_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'diploma_certificates' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // Single file
        'other_certificates' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // Single file
    ]);

    foreach ($courseApplications as $courseApplication) {
        // Handle single file uploads
        $uploadFields = ['ol_certificate', 'al_certificate', 'degree_certificate', 'transcript_certificate', 'diploma_certificates', 'other_certificates'];
        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                // Get the existing file path if it exists
                $oldPath = $courseApplication->$field;
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    // Delete the old file
                    Storage::disk('public')->delete($oldPath);
                }
                // Store the new file with the same naming convention as store method
                $file = $request->file($field);
                $filename = "{$field}_{$file->getClientOriginalName()}";
                $path = $file->storeAs($storagePath, $filename, 'public');
                $courseApplication->$field = $path;
            }
        }

        $courseApplication->status = 'Pending'; // Reset status to Pending after re-upload
        // Retain the rejection reason to show it was previously rejected
        $courseApplication->save();
    }

    return redirect()->back()->with('success', 'Documents updated successfully. Your application status has been set to Pending for review.');
}
}