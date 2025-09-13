<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['reportedBy', 'application'])->get();

        return view('frontend.complaints_list', compact('complaints'));
    }

    public function create()
    {
        $applications = Application::all(); // For reference or dropdown if needed
        return view('frontend.add_complaints', compact('applications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nic_number' => 'required|string|max:12',
            'complaint_type' => 'required|in:Financial Problem,Exam Problem,Disciplinary Problem,Result Problem,Other',
            'message' => 'required|string|max:1000',
        ]);

        $application = Application::where('nic_number', $request->nic_number)
                                ->orWhere('passport_number', $request->nic_number)
                                ->first();

        if (!$application || !$application->user) {
            return redirect()->back()->with('error', 'No user found with the provided NIC/Passport number.');
        }

        Complaint::create([
            'nic_number' => $request->nic_number,
            'complaint_type' => $request->complaint_type,
            'message' => $request->message,
            'reported_by' => Auth::id(), // Current authenticated admin
        ]);

        Log::info('Complaint added', [
            'nic_number' => $request->nic_number,
            'complaint_type' => $request->complaint_type,
            'message' => $request->message,
            'reported_by' => Auth::id(),
        ]);

        return redirect()->route('admin.complaints.create')->with('success', 'Complaint added successfully.');
    }
}