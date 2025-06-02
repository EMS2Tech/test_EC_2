<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function create()
    {
        return view('application.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'in:Mr,Ms,Mrs,Dr'],
            'full_name' => ['required', 'string', 'max:255'],
            'name_with_initials' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:255'],
            'nic_number' => ['required', 'string', 'max:255', 'unique:applications'],
            'nic_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'other_nationality' => ['nullable', 'string', 'max:255'],
            'passport_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'photograph' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'address' => ['required', 'string', 'max:500'],
            'contact_number' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email_address' => ['required', 'email', 'max:255', 'unique:applications'],
            'application_completed' => ['boolean'],
        ]);

        try {
            $applicationData = $validated;
            $applicationData['user_id'] = Auth::id();

            // Handle file uploads
            if ($request->hasFile('nic_photo')) {
                $applicationData['nic_photo'] = $request->file('nic_photo')->store('applications/nic', 'public');
            }
            if ($request->hasFile('passport_photo')) {
                $applicationData['passport_photo'] = $request->file('passport_photo')->store('applications/passport', 'public');
            }
            if ($request->hasFile('photograph')) {
                $applicationData['photograph'] = $request->file('photograph')->store('applications/photos', 'public');
            }

            $applicationData['application_completed'] = true;

            Application::create($applicationData);

            Log::info('Application submitted successfully', ['user_id' => Auth::id(), 'email' => $validated['email_address']]);

            return redirect()->route('user.dashboard')->with('status', 'Application submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Application submission failed', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to submit application. Please try again.');
        }
    }
}