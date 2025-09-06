<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CourseApplication;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $applications = User::select(
            'users.id as user_id',
            \DB::raw('COALESCE(MAX(applications.full_name), users.name) as full_name'),
            \DB::raw('MAX(applications.id) as application_id'),
            \DB::raw('MAX(applications.application_completed) as application_completed'),
            \DB::raw('MAX(applications.status) as status'), // Add status column
            \DB::raw('MAX(study_programs.program_name) as study_programme_name'),
            \DB::raw('MAX(courses.course_name) as course_name'),
            \DB::raw('(
                SELECT CASE
                    WHEN MAX(payments.payment_slip) IS NULL THEN "Not Complete"
                    WHEN MAX(CASE WHEN payments.status = "Pending Verification" THEN 1 ELSE 0 END) = 1 THEN "Pending Verification"
                    WHEN MAX(CASE WHEN payments.status = "Completed" THEN 1 ELSE 0 END) = 1 THEN "Completed"
                    ELSE "Not Complete"
                END
                FROM payments
                WHERE payments.user_id = users.id
            ) as payment_status'),
            \DB::raw('COALESCE(MAX(students.student_id), "N/A") as student_id')
        )
        ->where('users.type', 'student')
        ->leftJoin('applications', 'users.id', '=', 'applications.user_id')
        ->leftJoin('course_applications', 'users.id', '=', 'course_applications.user_id')
        ->leftJoin('study_programs', 'course_applications.study_programme_id', '=', 'study_programs.id')
        ->leftJoin('courses', 'course_applications.course_id', '=', 'courses.id')
        ->leftJoin('students', 'users.id', '=', 'students.user_id')
        ->groupBy('users.id', 'users.name')
        ->paginate(10); // 10 items per page

        $currentPage = $applications->currentPage();
        $lastPage = $applications->lastPage();

        return view('frontend.admin', compact('applications', 'currentPage', 'lastPage'));
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

        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}