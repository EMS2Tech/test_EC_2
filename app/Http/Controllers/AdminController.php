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
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $studyPrograms = StudyProgram::withCount([
            'courseApplications as total_count' => function ($query) {
                $query->select(DB::raw('COUNT(*)'));
            },
            'courseApplications as approved_count' => function ($query) {
                $query->where('status', 'Approved');
            },
            'courseApplications as pending_count' => function ($query) {
                $query->where('status', 'Pending');
            },
        ])->get();

        return view('frontend.admin', compact('students','studyPrograms'));

        

        
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
        'status',
        'updated_by'
    )->leftJoin('users', 'applications.user_id', '=', 'users.id');

    // Search logic
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('nic_number', 'like', "%{$search}%")
              ->orWhere('passport_number', 'like', "%{$search}%")
              ->orWhere('applications.id', 'like', "%{$search}%")
              ->orWhere('email_address', 'like', "%{$search}%");
        });
    }

    // Status filter logic
    if ($status = $request->input('status')) {
        $query->where('status', $status);
    }

    // Updated By filter logic
    if ($updatedBy = $request->input('updated_by')) {
        $query->where('updated_by', $updatedBy);
    }

    // Date range filter logic
    $dateRange = $request->input('date_range');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if ($dateRange) {
        $now = \Carbon\Carbon::now('Asia/Colombo');
        if ($dateRange === 'last_24h') {
            $query->where('applications.created_at', '>=', $now->subHours(24));
        } elseif ($dateRange === 'last_7d') {
            $query->where('applications.created_at', '>=', $now->subDays(7));
        } elseif ($dateRange === 'last_month') {
            $query->where('applications.created_at', '>=', $now->subMonth());
        } elseif ($dateRange === 'custom' && $startDate && $endDate) {
            $start = \Carbon\Carbon::parse($startDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('applications.created_at', [$start, $end]);
        }
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

    // Apply search by NIC/passport number or full name
    if ($request->has('search')) {
        $search = $request->search;
        $query->whereHas('user.application', function ($q) use ($search) {
            $q->where('full_name', 'like', '%' . $search . '%')
              ->orWhere('nic_number', 'like', '%' . $search . '%')
              ->orWhere('passport_number', 'like', '%' . $search . '%');
        });
    }

    $courseApplications = $query->paginate(10);
    $currentPage = $request->input('page', 1);
    $lastPage = $courseApplications->lastPage();

    return view('frontend.course-application', compact('courseApplications', 'currentPage', 'lastPage'));
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
                  ->select('course_applications.user_id');
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
            DB::raw('COALESCE((SELECT COUNT(*) FROM course_applications WHERE course_applications.user_id = students.user_id), 0) as no_of_courses')
        )
        ->leftJoin('applications', 'students.user_id', '=', 'applications.user_id')
        ->leftJoin('users', 'students.user_id', '=', 'users.id');

        // Apply filters to match studentsIndex
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
        if ($request->filled('no_of_courses')) {
            $noOfCourses = $request->input('no_of_courses');
            $query->whereExists(function ($q) use ($request, $noOfCourses) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->groupBy('course_applications.user_id');
                if ($noOfCourses === '6+') {
                    $q->havingRaw('COUNT(*) >= 6');
                } else {
                    $q->havingRaw('COUNT(*) = ?', [(int)$noOfCourses]);
                }
            });
        }

        $students = $query->get();
        Log::info('Students fetched for export', ['count' => $students->count(), 'url' => url()->current()]);

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

public function showAddManagerForm()
    {
        $managers = User::whereIn('type', ['manager', 'finance_manager', 'course_manager', 'front_manager'])->get();
        return view('frontend.addmanager', compact('managers'));
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:finance_manager,course_manager,front_manager',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        return redirect()->route('admin.manager.add')->with('success', 'Manager added successfully.');
    }

    public function deleteManager($id)
    {
        $user = User::findOrFail($id);

        if (!in_array($user->type, ['manager', 'finance_manager', 'course_manager', 'front_manager'])) {
            return redirect()->route('admin.manager.add')->with('error', 'Cannot delete this user type.');
        }

        $user->delete();

        return redirect()->route('admin.manager.add')->with('success', 'Manager deleted successfully.');
    }

    public function dashboard()
    {
        
    }

}