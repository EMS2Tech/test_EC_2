<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    public function create()
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();

        if ($application && $application->application_completed) {
            return redirect()->route('profile.edit')->with('error', 'You have already submitted an application.');
        }

        $application = $application ?? new Application(['user_id' => $user->id]);
        return view('application.application', compact('application'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if application already completed
        if (Application::where('user_id', $user->id)->where('application_completed', true)->exists()) {
            return redirect()->route('profile.edit')->with('error', 'You have already submitted an application.');
        }

        $rules = [
            'title' => 'required|in:Mr,Mrs,Miss,Rev',
            'full_name' => 'required|string|max:255',
            'name_with_initials' => 'required|string|max:255',
            'birthday' => 'required|date|before:today',
            'nationality' => 'required|in:Sri Lanka,Other',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:10|regex:/^07[0-9]{8}$/',
            'whatsapp_number' => 'nullable|string|max:10|regex:/^07[0-9]{8}$/',
            'email_address' => 'required|email|max:255',
            'photograph' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ];

        // Conditional validation based on nationality
        if ($request->nationality === 'Sri Lanka') {
            $rules['nic_number'] = 'required|string|max:12';
            $rules['nic_photo'] = 'required|image|mimes:jpeg,png,jpg|max:4096';
        } else {
            $rules['other_nationality'] = 'required|string|max:255';
            $rules['passport_photo'] = 'required|image|mimes:jpeg,png,jpg|max:4096';
        }

        $request->validate($rules);

        $application = Application::where('user_id', $user->id)->first() ?? new Application(['user_id' => $user->id]);

        // Handle file uploads
        if ($request->hasFile('nic_photo')) {
            $application->nic_photo = $request->file('nic_photo')->store('applications/nic', 'public');
        }
        if ($request->hasFile('passport_photo')) {
            $application->passport_photo = $request->file('passport_photo')->store('applications/passport', 'public');
        }
        if ($request->hasFile('photograph')) {
            $application->photograph = $request->file('photograph')->store('applications/photos', 'public');
        }

        // Fill application data
        $application->fill([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'name_with_initials' => $request->name_with_initials,
            'birthday' => $request->birthday,
            'nationality' => $request->nationality,
            'nic_number' => $request->nationality === 'Sri Lanka' ? $request->nic_number : null,
            'other_nationality' => $request->nationality === 'Other' ? $request->other_nationality : null,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'whatsapp_number' => $request->whatsapp_number,
            'email_address' => $request->email_address,
            'application_completed' => true,
        ]);

        $application->save();

        Log::info('Application completed', ['user_id' => $user->id]);

        return redirect()->route('profile.edit')->with('status', 'Application submitted successfully!');
    }

    public function updatePhotograph(Request $request)
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'No application found for this user.'], 404);
        }

        $request->validate([
            'photograph' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        // Delete old photograph if exists
        if ($application->photograph) {
            Storage::disk('public')->delete($application->photograph);
        }

        // Store new photograph
        $path = $request->file('photograph')->store('applications/photos', 'public');
        $application->photograph = $path;
        $application->save();

        Log::info('Profile photograph updated', ['user_id' => $user->id, 'path' => $path]);

        return response()->json([
            'success' => true,
            'newPhotographUrl' => asset('storage/' . $path)
        ]);
    }
}