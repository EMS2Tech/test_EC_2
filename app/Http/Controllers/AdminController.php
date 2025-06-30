<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CourseApplication;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $applications = User::select(
            'users.id as user_id',
            \DB::raw('COALESCE(MAX(applications.full_name), users.name) as full_name'),
            \DB::raw('MAX(applications.id) as application_id'),
            \DB::raw('MAX(applications.application_completed) as application_completed'),
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
        ->paginate(5); // 5 items per page

        $currentPage = $applications->currentPage();
        $lastPage = $applications->lastPage();

        return view('frontend.admin', compact('applications', 'currentPage', 'lastPage'));
    }
    
    public function applications(Request $request)
    {
        $query = Application::select(
            'applications.user_id',
            'full_name',
            'nationality',
            'other_nationality',
            'nic_number',
            'contact_number',
            'email_address'
        )
        ->leftJoin('payments', 'applications.user_id', '=', 'payments.user_id');

        // Search logic
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nic_number', 'like', "%{$search}%")
                  ->orWhere('applications.user_id', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(5);

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
}