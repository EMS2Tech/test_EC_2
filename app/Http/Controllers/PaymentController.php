<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Application;
use App\Http\Requests\PaymentStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\PaymentStatusUpdate;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Assuming a role middleware for admin access
    }

    public function verify()
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)->get();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
            return redirect()->route('application.create')->with('error', 'Please complete your application first.');
        }

        return view('frontend.user-payment', compact('payments', 'application'));
    }

    public function manage(Request $request)
    {
        $query = Payment::with('user.application')->latest();

        // Debugging: Log the incoming request parameters
        Log::info('Manage Payments Request', $request->all());

        // Apply search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user.application', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
            Log::info('Applied search filter', ['search' => $search]);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
            Log::info('Applied status filter', ['status' => $request->input('status')]);
        }

        // Apply payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->input('payment_type'));
            Log::info('Applied payment type filter', ['payment_type' => $request->input('payment_type')]);
        }

        // Apply date range filter
        if ($request->filled('date_range')) {
            $today = Carbon::now('Asia/Colombo');
            Log::info('Applying date range filter', ['date_range' => $request->date_range, 'start_date' => $request->start_date, 'end_date' => $request->end_date]);
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
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $start = Carbon::parse($request->start_date)->startOfDay();
                        $end = Carbon::parse($request->end_date)->endOfDay();
                        if ($start->lte($end)) {
                            $query->whereBetween('created_at', [$start, $end]);
                            Log::info('Applied custom date range', ['start' => $start, 'end' => $end]);
                        } else {
                            Log::warning('Invalid custom date range: start date after end date', ['start' => $start, 'end' => $end]);
                        }
                    } else {
                        Log::warning('Custom date range selected but start_date or end_date missing', ['start_date' => $request->start_date, 'end_date' => $request->end_date]);
                    }
                    break;
                default:
                    Log::warning('Invalid date range value', ['date_range' => $request->date_range]);
            }
        }

        $payments = $query->paginate(10);
        Log::info('Payments retrieved', ['total' => $payments->total(), 'current_page' => $payments->currentPage(), 'last_page' => $payments->lastPage()]);

        $currentPage = $payments->currentPage();
        $lastPage = $payments->lastPage();

        // Pass the request parameters back to the view to maintain filter state
        return view('frontend.admin_payment', compact('payments', 'currentPage', 'lastPage'))->with($request->all());
    }

    public function export(Request $request)
    {
        $query = Payment::with('user.application')->latest();

        // Apply filters from the request
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user.application', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('payment_type') && $request->input('payment_type') !== '') {
            $query->where('payment_type', $request->input('payment_type'));
        }

        // Apply date range filter
        if ($request->has('date_range')) {
            $now = Carbon::now('Asia/Colombo');
            switch ($request->input('date_range')) {
                case 'last_24h':
                    $query->where('created_at', '>=', $now->subHours(24));
                    break;
                case 'last_7d':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case 'last_month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        if ($startDate->lte($endDate)) {
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }
                    }
                    break;
            }
        }

        $payments = $query->get();

        // Generate CSV content
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments_export_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['User Full Name', 'Uploaded At', 'Payment Type', 'Remark', 'Status', 'Rejection Reason']);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->user->application->full_name ?? 'N/A',
                    $payment->created_at ? $payment->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $payment->payment_type ?? 'N/A',
                    $payment->remark ?? 'N/A',
                    $payment->status ?? 'Pending',
                    $payment->rejection_reason ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function details($id)
    {
        $payment = Payment::with('user.application')->findOrFail($id);
        return view('frontend.paymentdetails', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
            'rejection_reason' => 'required_if:status,Rejected|string|max:255',
        ]);

        $payment->status = $request->input('status');
        if ($request->input('status') === 'Rejected' && $request->filled('rejection_reason')) {
            $payment->rejection_reason = $request->input('rejection_reason');
        } elseif ($request->input('status') === 'Rejected' && !$request->filled('rejection_reason')) {
            return redirect()->back()->with('error', 'Rejection reason is required when rejecting a payment.');
        } else {
            $payment->rejection_reason = null;
        }

        // Set the admin who updated the status
        $payment->updated_by = Auth::id(); // Assuming the admin is authenticated

        $payment->save();

        Log::info('Payment status updated', [
            'payment_id' => $payment->id,
            'user_id' => $payment->user_id,
            'status' => $payment->status,
            'rejection_reason' => $payment->rejection_reason,
            'updated_by' => $payment->updated_by,
        ]);

        // Send email notification
        if (in_array($payment->status, ['Approved', 'Rejected'])) {
            Mail::to($payment->user->email)->send(new PaymentStatusUpdate($payment, $payment->status));
            Log::info('Email notification sent for payment status update', [
                'payment_id' => $payment->id,
                'user_email' => $payment->user->email,
                'status' => $payment->status,
            ]);
        }

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
            return redirect()->route('application.create')->with('error', 'Please complete your application first.');
        }

        $request->validate([
            'payment_type' => 'required|in:registration,course',
            'payment_slip' => 'required|file|mimes:pdf,png,jpg,jpeg|max:4096',
            'remark' => 'required|string|max:20',
        ]);

        if ($request->hasFile('payment_slip')) {
            $file = $request->file('payment_slip');
            $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
            $storagePath = "applications/{$folderName}/payments";

            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            if ($file->isValid()) {
                $fileName = "payment_slip_" . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($storagePath, $fileName, 'public');

                Payment::create([
                    'user_id' => $user->id,
                    'application_id' => $application->id,
                    'status' => 'Pending',
                    'payment_slip' => $path,
                    'payment_type' => $request->input('payment_type'),
                    'remark' => $request->input('remark'),
                    'created_at' => now(),
                ]);

                Log::info('Payment slip uploaded', [
                    'user_id' => $user->id,
                    'path' => $path,
                    'payment_type' => $request->input('payment_type'),
                    'remark' => $request->input('remark'),
                ]);

                return redirect()->back()->with('success', 'Payment slip uploaded successfully.');
            } else {
                Log::info('Invalid payment slip upload', ['file' => $file]);
                return redirect()->back()->with('error', 'Invalid payment slip file.')->withInput();
            }
        }

        return redirect()->back()->with('error', 'Please upload a payment slip.');
    }

    public function showUpdateForm($id)
    {
        $payment = Payment::where('user_id', auth()->id())->findOrFail($id);
        if ($payment->status !== 'Rejected') {
            return redirect()->back()->with('error', 'Only rejected payments can be updated.');
        }

        return view('frontend.user_payment_update', compact('payment'));
    }

    public function updateUserPayment(Request $request, $id)
    {
        $payment = Payment::where('user_id', auth()->id())->findOrFail($id);
        if ($payment->status !== 'Rejected') {
            return redirect()->back()->with('error', 'Only rejected payments can be updated.');
        }

        $request->validate([
            'payment_slip' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:4096',
            'remark' => 'required|string|max:20',
        ]);

        $data = ['remark' => $request->input('remark')];
        $folderName = $payment->user->application->nic_number ?: ($payment->user->application->passport_number ?: ($payment->user_id . '_' . time()));
        $storagePath = "applications/{$folderName}/payments";

        if ($request->hasFile('payment_slip')) {
            if ($payment->payment_slip) {
                Storage::disk('public')->delete($payment->payment_slip);
            }
            $file = $request->file('payment_slip');
            if ($file->isValid()) {
                $fileName = "payment_slip_" . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($storagePath, $fileName, 'public');
                $data['payment_slip'] = $path;
            }
        }

        $payment->update($data + ['status' => 'Pending']);

        return redirect()->route('profile.edit')->with('success', 'Payment updated successfully and set to pending for review.');
    }
}