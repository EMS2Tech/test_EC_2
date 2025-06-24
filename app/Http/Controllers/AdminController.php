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
            \DB::raw('COALESCE(applications.full_name, CONCAT(users.name)) as full_name'),
            'applications.id as application_id',
            'applications.application_completed',
            'course_applications.study_programme',
            'course_applications.course',
            \DB::raw('(
                SELECT CASE
                    WHEN MAX(payments.payment_slip) IS NULL THEN "Not Complete"
                    WHEN MAX(CASE WHEN payments.status = "Pending Verification" THEN 1 ELSE 0 END) = 1 THEN "Pending Verification"
                    WHEN MAX(CASE WHEN payments.status = "Completed" THEN 1 ELSE 0 END) = 1 THEN "Completed"
                    ELSE "Not Complete"
                END
                FROM payments
                WHERE payments.user_id = users.id
            ) as payment_status')
        )
        ->where('users.type', 'student')
        ->leftJoin('applications', 'users.id', '=', 'applications.user_id')
        ->leftJoin('course_applications', 'users.id', '=', 'course_applications.user_id')
        ->groupBy('users.id', 'users.name', 'applications.full_name', 'applications.id', 'applications.application_completed', 'course_applications.study_programme', 'course_applications.course')
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
        $courseApplication = CourseApplication::where('user_id', $application->user_id)->first();

        return view('frontend.application-view', compact('application', 'payments', 'courseApplication'));
    }
}