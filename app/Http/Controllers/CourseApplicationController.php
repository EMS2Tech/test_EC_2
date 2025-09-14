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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Mail\CourseApplicationStatus;
use Illuminate\Support\Facades\Mail;

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

                $studentId = "{$shortName}-{$currentYear}-{$applicationNo}";
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

        // Send email notification
        if (in_array($status, ['Approved', 'Rejected'])) {
            Mail::to($courseApplication->user->email)->send(new CourseApplicationStatus($courseApplication, $status));
            Log::info('Email notification sent for course application status update', [
                'course_application_id' => $courseApplication->id,
                'user_email' => $courseApplication->user->email,
                'status' => $status,
            ]);
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

public function export(Request $request)
{
    $query = DB::table('course_applications as ca')
        ->select(
            'ca.id',
            'ca.user_id',
            'ca.created_at as apply_date',
            'ca.status',
            'sp.program_name as study_programme_name',
            'c.course_name',
            'b.batch_no',
            DB::raw('COALESCE(a.full_name, u.name) as full_name'),
            DB::raw('COALESCE(a.nic_number, a.passport_number) as nic_passport') // Combine NIC and Passport into one column
        )
        ->leftJoin('study_programs as sp', 'ca.study_programme_id', '=', 'sp.id')
        ->leftJoin('courses as c', 'ca.course_id', '=', 'c.id')
        ->leftJoin('batches as b', 'ca.batch_id', '=', 'b.id')
        ->leftJoin('applications as a', 'ca.user_id', '=', 'a.user_id')
        ->leftJoin('users as u', 'ca.user_id', '=', 'u.id');

    // Apply filters
    if ($request->filled('study_program_name')) {
        $query->where('sp.program_name', 'like', '%' . $request->input('study_program_name') . '%');
    }
    if ($request->filled('course_name')) {
        $query->where('c.course_name', 'like', '%' . $request->input('course_name') . '%');
    }
    if ($request->filled('batch_no')) {
        $query->where('b.batch_no', 'like', '%' . $request->input('batch_no') . '%');
    }
    if ($request->filled('status')) {
        $query->where('ca.status', $request->input('status'));
    }

    // Apply date range filter
    if ($request->filled('date_range')) {
        $now = Carbon::now('Asia/Colombo');
        switch ($request->input('date_range')) {
            case 'last_24h':
                $query->where('ca.created_at', '>=', $now->subHours(24));
                break;
            case 'last_7d':
                $query->where('ca.created_at', '>=', $now->subDays(7));
                break;
            case 'last_month':
                $query->where('ca.created_at', '>=', $now->subMonth());
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                    if ($startDate->lte($endDate)) {
                        $query->whereBetween('ca.created_at', [$startDate, $endDate]);
                    }
                }
                break;
        }
    }

    // Apply search filter for NIC/Passport or full name
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('a.full_name', 'like', '%' . $search . '%')
              ->orWhere('a.nic_number', 'like', '%' . $search . '%')
              ->orWhere('a.passport_number', 'like', '%' . $search . '%')
              ->orWhere(DB::raw('COALESCE(a.nic_number, a.passport_number)'), 'like', '%' . $search . '%');
        });
    }

    // Fetch the filtered data
    $applications = $query->get();

    // Debug the raw data
    \Log::info('Export Data: ' . json_encode($applications->toArray()));

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="course_applications_' . date('Ymd_His') . '.csv"',
    ];

    $callback = function () use ($applications) {
        $output = fopen('php://output', 'w');

        // Add CSV header (replaced NIC and Passport with NIC/Passport)
        fputcsv($output, ['User Full Name', 'Study Programme Name', 'Course Name', 'Batch No', 'Apply Date', 'Status', 'NIC/Passport']);

        // Add data rows
        foreach ($applications as $application) {
            // Convert created_at to a formatted date if it's a string or Carbon instance
            $applyDate = $application->apply_date;
            if ($applyDate && !$applyDate instanceof Carbon) {
                $applyDate = Carbon::parse($applyDate)->format('Y-m-d H:i:s');
            } elseif ($applyDate) {
                $applyDate = $applyDate->format('Y-m-d H:i:s');
            } else {
                $applyDate = 'N/A';
            }

            fputcsv($output, [
                $application->full_name ?? 'N/A',
                $application->study_programme_name ?? 'N/A',
                $application->course_name ?? 'N/A',
                $application->batch_no ?? 'N/A',
                $applyDate,
                $application->status ?? 'N/A',
                $application->nic_passport ?? 'N/A', // Use the combined NIC/Passport column
            ]);
        }

        fclose($output);
    };

    return Response::stream($callback, 200, $headers);
}
}