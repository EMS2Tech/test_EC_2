<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CourseApplication;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\Batch;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $students = Student::select(
            'students.id',
            'students.student_id',
            DB::raw('COALESCE(applications.full_name, users.name, "N/A") as full_name'),
            DB::raw('COALESCE(applications.contact_number, "N/A") as contact_number'),
            DB::raw('COALESCE(applications.nic_number, applications.passport_number, "N/A") as nic_number'),
            DB::raw('COALESCE(applications.email_address, users.email, "N/A") as email'),
            DB::raw('COALESCE(applications.photograph, NULL) as photograph')
        )
        ->join('users', 'students.user_id', '=', 'users.id')
        ->leftJoin('applications', 'students.user_id', '=', 'applications.user_id')
        ->where('users.type', 'student')
        ->orderBy('students.id')
        ->paginate(10); // 10 items per page

        return view('frontend.admin', compact('students'));
    }
    
    public function applications(Request $request)
    {
        $query = Application::select(
            'applications.id',
            'applications.user_id',
            'full_name',
            'contact_number',
            'email_address',
            'nic_number',
            'passport_number',
            'nationality',
            'status'
        )->leftJoin('users', 'applications.user_id', '=', 'users.id');

        // Search logic
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nic_number', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%")
                  ->orWhere('applications.id', 'like', "%{$search}%") // Qualify id with applications table
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        // Status filter logic
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $applications = $query->paginate(20);

        $currentPage = $applications->currentPage();
        $lastPage = $applications->lastPage();

        return view('frontend.admin-application', compact('applications', 'currentPage', 'lastPage'));
    }

    public function updatePaymentStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected',
        ]);

        $payment = Payment::where('user_id', $userId)->latest()->first();
        if ($payment) {
            $payment->status = $request->status === 'completed' ? 'Completed' : 'Pending Verification';
            $payment->save();

            return redirect()->back()->with('success', 'Payment status updated successfully.');
        }

        return redirect()->back()->with('error', 'No payment record found for this user.');
    }

    public function viewApplication($id)
    {
        $application = Application::where('id', $id)->orWhere('user_id', $id)->firstOrFail();
        $payments = Payment::where('user_id', $application->user_id)->get();
        $courseApplications = CourseApplication::where('user_id', $application->user_id)->get();

        return view('frontend.application-view', compact('application', 'payments', 'courseApplications'));
    }

    public function courseApplications(Request $request)
    {
        $query = CourseApplication::with(['user.application', 'studyProgram', 'course.batches']);

        // Apply filters
        if ($request->has('study_program_name')) {
            $query->whereHas('studyProgram', function ($q) use ($request) {
                $q->where('program_name', 'like', '%' . $request->study_program_name . '%');
            });
        }
        if ($request->has('course_name')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_name', 'like', '%' . $request->course_name . '%');
            });
        }
        if ($request->has('batch_no')) {
            $query->whereHas('course.batches', function ($q) use ($request) {
                $q->where('batch_no', 'like', '%' . $request->batch_no . '%');
            });
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('date_range')) {
            $today = Carbon::now('Asia/Colombo');
            switch ($request->date_range) {
                case 'last_24h':
                    $query->where('created_at', '>=', $today->subHours(24));
                    break;
                case 'last_7d':
                    $query->where('created_at', '>=', $today->subDays(7));
                    break;
                case 'last_month':
                    $query->where('created_at', '>=', $today->subMonth());
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $start = Carbon::parse($request->start_date)->startOfDay();
                        $end = Carbon::parse($request->end_date)->endOfDay();
                        $query->whereBetween('created_at', [$start, $end]);
                    }
                    break;
            }
        }

        $courseApplications = $query->paginate(10);
        $currentPage = $request->input('page', 1);
        $lastPage = $courseApplications->lastPage();

        return view('frontend.course-application', compact('courseApplications', 'currentPage', 'lastPage'));
    }

    public function export(Request $request)
    {
        $query = CourseApplication::select(
            'course_applications.id',
            'course_applications.user_id',
            'course_applications.created_at as apply_date',
            'study_programs.program_name as study_programme_name',
            'courses.course_name as course_name',
            \DB::raw('GROUP_CONCAT(batches.batch_no SEPARATOR ", ") as batch_no'),
            \DB::raw('COALESCE(applications.full_name, users.name) as full_name')
        )
        ->leftJoin('study_programs', 'course_applications.study_programme_id', '=', 'study_programs.id')
        ->leftJoin('courses', 'course_applications.course_id', '=', 'courses.id')
        ->leftJoin('batches', 'courses.id', '=', 'batches.course_id')
        ->leftJoin('applications', 'course_applications.user_id', '=', 'applications.user_id')
        ->leftJoin('users', 'course_applications.user_id', '=', 'users.id')
        ->groupBy(
            'course_applications.id',
            'course_applications.user_id',
            'course_applications.created_at',
            'study_programs.program_name',
            'courses.course_name',
            'applications.full_name',
            'users.name'
        );

        // Apply all filters from the request
        if ($request->filled('study_program_name')) {
            $query->where('study_programs.program_name', 'like', '%' . $request->input('study_program_name') . '%');
        }
        if ($request->filled('course_name')) {
            $query->where('courses.course_name', 'like', '%' . $request->input('course_name') . '%');
        }
        if ($request->filled('batch_no')) {
            $query->where('batches.batch_no', 'like', '%' . $request->input('batch_no') . '%');
        }

        // Apply date range filter
        if ($request->filled('date_range')) {
            $now = Carbon::now();
            switch ($request->input('date_range')) {
                case 'last_24h':
                    $query->where('course_applications.created_at', '>=', $now->subHours(24));
                    break;
                case 'last_7d':
                    $query->where('course_applications.created_at', '>=', $now->subDays(7));
                    break;
                case 'last_month':
                    $query->where('course_applications.created_at', '>=', $now->subMonth());
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        if ($startDate->lte($endDate)) {
                            $query->whereBetween('course_applications.created_at', [$startDate, $endDate]);
                        }
                    }
                    break;
            }
        }

        // Fetch the filtered data
        $applications = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="course_applications_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($applications) {
            $output = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($output, ['User Full Name', 'Study Programme Name', 'Course Name', 'Batch No', 'Apply Date']);

            // Add data rows
            foreach ($applications as $application) {
                fputcsv($output, [
                    $application->full_name,
                    $application->study_programme_name,
                    $application->course_name,
                    $application->batch_no ?? 'N/A',
                    $application->apply_date,
                ]);
            }

            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function viewApplicationDetails($id)
    {
        $application = Application::where('id', $id)->orWhere('user_id', $id)->firstOrFail();
        $payments = Payment::where('user_id', $application->user_id)->get();
        $courseApplications = CourseApplication::where('user_id', $application->user_id)->get();

        return view('frontend.application-view', compact('application', 'payments', 'courseApplications'));
    }

    public function updateApplicationStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Approved,Rejected',
        'reason' => 'required_if:status,Rejected|string|max:500',
    ]);

    $application = Application::where('id', $id)->orWhere('user_id', $id)->firstOrFail();

    $application->status = $request->status;
    if ($request->status === 'Rejected') {
        $application->rejection_reason = $request->reason;
    } else {
        $application->rejection_reason = null; // Clear rejection reason on approval
    }

    // Set the admin who updated the status
    $application->updated_by = Auth::id();

    $application->save();

    Log::info('Application status updated', [
        'application_id' => $application->id,
        'user_id' => $application->user_id,
        'status' => $application->status,
        'rejection_reason' => $application->rejection_reason,
        'updated_by' => $application->updated_by,
    ]);

    return redirect()->back()->with('success', 'Application status updated successfully.');
}








    public function studentsIndex(Request $request)
{
    $query = Student::with(['user', 'application', 'application.courseApplications']);

    // Apply search by Student ID or NIC
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
              ->orWhereHas('application', function ($q2) use ($search) {
                  $q2->where('nic_number', 'like', "%{$search}%")
                     ->orWhere('passport_number', 'like', "%{$search}%");
              });
        });
        Log::info('Applied student search filter', ['search' => $search]);
    }

    // Apply filters
    if ($request->filled('study_program')) {
        $query->whereHas('application.courseApplications', function ($q) use ($request) {
            $q->where('study_programme_id', $request->input('study_program'));
        });
    }
    if ($request->filled('course')) {
        $query->whereHas('application.courseApplications', function ($q) use ($request) {
            $q->where('course_id', $request->input('course'));
        });
    }
    if ($request->filled('batch')) {
        $query->whereHas('application.courseApplications', function ($q) use ($request) {
            $q->where('batch_id', $request->input('batch'));
        });
    }
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereHas('application', function ($q) use ($request) {
            $q->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
        });
    }
    if ($request->filled('no_of_courses')) {
        $noOfCourses = $request->input('no_of_courses');
        $query->whereHas('application.courseApplications', function ($q) use ($noOfCourses) {
            $q->groupBy('course_applications.user_id')
              ->select('course_applications.user_id'); // Select only grouped column
            if ($noOfCourses === '6+') {
                $q->havingRaw('COUNT(*) >= 6');
            } else {
                $q->havingRaw('COUNT(*) = ?', [$noOfCourses]);
            }
        });
    }

    $students = $query->paginate(10);
    $currentPage = $students->currentPage();
    $lastPage = $students->lastPage();

    // Fetch filter options
    $studyPrograms = StudyProgram::all();
    $courses = Course::all();
    $batches = Batch::all();

    return view('frontend.students_index', compact('students', 'currentPage', 'lastPage', 'studyPrograms', 'courses', 'batches'))->with($request->all());
}

    public function studentDetails($id)
    {
        $student = Student::with([
            'user',
            'application',
            'application.courseApplications.studyProgram',
            'application.courseApplications.course.batches',
            'application.payments'
        ])->findOrFail($id);
        return view('frontend.student_details', compact('student'));
    }

    public function studentsExport(Request $request)
    {
        Log::info('Students export initiated', $request->all());

        $query = Student::select(
            'students.student_id',
            'students.full_name',
            DB::raw('COALESCE(applications.nic_number, applications.passport_number, "N/A") as nic_or_passport'),
            DB::raw('COALESCE(applications.contact_number, "N/A") as contact_number'),
            DB::raw('(SELECT COUNT(*) FROM course_applications WHERE course_applications.user_id = students.user_id) as no_of_courses')
        )
        ->leftJoin('applications', 'students.user_id', '=', 'applications.user_id')
        ->leftJoin('users', 'students.user_id', '=', 'users.id');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('students.student_id', 'like', "%{$search}%")
                  ->orWhere('students.full_name', 'like', "%{$search}%")
                  ->orWhere('applications.nic_number', 'like', "%{$search}%")
                  ->orWhere('applications.passport_number', 'like', "%{$search}%");
            });
            Log::info('Applied export search filter', ['search' => $search]);
        }
        if ($request->filled('study_program')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.study_programme_id', $request->input('study_program'));
            });
        }
        if ($request->filled('course')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.course_id', $request->input('course'));
            });
        }
        if ($request->filled('batch')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.batch_id', $request->input('batch'));
            });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('applications')
                  ->whereColumn('applications.user_id', 'students.user_id')
                  ->whereBetween('applications.created_at', [$request->input('start_date'), $request->input('end_date')]);
            });
        }

        $students = $query->get();
        Log::info('Students fetched for export', ['count' => $students->count()]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($students) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Student ID', 'Full Name', 'NIC or Passport', 'Contact Number', 'No of Courses']);
            foreach ($students as $student) {
                fputcsv($output, [
                    $student->student_id ?? 'N/A',
                    $student->full_name ?? 'N/A',
                    $student->nic_or_passport ?? 'N/A',
                    $student->contact_number ?? 'N/A',
                    $student->no_of_courses ?? 0,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }

    





    public function showPaymentRequestForm()
    {
        $courses = Course::all();
        return view('frontend.payment_request', compact('courses'));
    }

    public function sendPaymentRequest(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'batch_id' => 'required|exists:batches,id',
        'message' => 'required|string',
    ]);

    $courseApplications = CourseApplication::where('course_id', $request->course_id)
        ->where('batch_id', $request->batch_id)
        ->get();

    foreach ($courseApplications as $application) {
        PaymentRequest::updateOrCreate(
            [
                'user_id' => $application->user_id,
                'course_id' => $request->course_id,
                'batch_id' => $request->batch_id,
            ],
            [
                'status' => 'pending',
                'message' => $request->message,
                'updated_at' => now(),
            ]
        );
    }

    return back()->with('success', 'Payment request sent to all users in the selected course and batch.');
}

    public function getCourses(Request $request)
{
    $studyProgramId = $request->input('study_program_id');
    $courses = Course::where('program_id', $studyProgramId)->get(['id', 'course_name']);
    return response()->json(['courses' => $courses]);
}

public function getBatches(Request $request)
{
    $courseId = $request->input('course_id');
    $batches = Batch::where('course_id', $courseId)->get(['id', 'batch_no']);
    return response()->json(['batches' => $batches]);
}

}