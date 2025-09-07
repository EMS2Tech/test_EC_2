<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Application;
use App\Http\Requests\PaymentStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
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
        'remark' => 'nullable|string|max:255',
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
                'status' => 'Pending Verification',
                'payment_slip' => $path,
                'payment_type' => $request->input('payment_type'),
                'remark' => $request->input('remark'),
                'created_at' => now(), // Manually set created_at
            ]);

            Log::info('Payment slip uploaded', [
                'user_id' => $user->id,
                'path' => $path,
                'payment_type' => $request->input('payment_type'),
                'remark' => $request->input('remark'),
            ]);

            return redirect()->back()->with('success', 'Payment slip uploaded successfully.');
        } else {
            Log::error('Invalid payment slip upload', ['file' => $file]);
            return redirect()->back()->with('error', 'Invalid payment slip file.')->withInput();
        }
    }

    return redirect()->back()->with('error', 'Please upload a payment slip.');
}
}