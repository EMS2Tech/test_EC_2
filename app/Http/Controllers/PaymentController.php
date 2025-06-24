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

    public function store(PaymentStoreRequest $request)
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
            return redirect()->route('application.create')->with('error', 'Please complete your application first.');
        }

        if ($request->hasFile('payment_slip')) {
            $file = $request->file('payment_slip');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/payments', $fileName, 'public');

            Payment::create([
                'user_id' => $user->id,
                'application_id' => $application->id,
                'status' => 'Pending Verification',
                'payment_slip' => $path,
            ]);

            Log::info('Payment slip uploaded', ['user_id' => $user->id, 'path' => $path]);

            return redirect()->back()->with('success', 'Payment slip uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Please upload a payment slip.');
    }
}