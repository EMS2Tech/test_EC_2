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
            return redirect()->route('course-application.create')->with('error', 'You have already submitted an application.');
        }

        $rules = [
            'title' => 'required|in:Mr,Mrs,Miss,Rev',
            'full_name' => 'required|string|max:255',
            'name_with_initials' => 'required|string|max:255',
            'birthday' => 'required|date|before:today',
            'nationality' => 'required|in:Sri Lanka,Other',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
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
            $rules['passport_number'] = 'required|string|max:20';
            $rules['passport_photo'] = 'required|image|mimes:jpeg,png,jpg|max:4096';
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $application = Application::where('user_id', $user->id)->first() ?? new Application(['user_id' => $user->id]);

        // Determine folder name based on nationality
        $folderName = $request->nationality === 'Sri Lanka' && $request->nic_number
            ? $request->nic_number
            : ($request->nationality === 'Other' && $request->passport_number
                ? $request->passport_number
                : $user->id . '_' . time()); // Fallback: user_id_timestamp

        // Ensure the folder exists with the applications prefix
        $storagePath = "applications/{$folderName}";
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        // Handle file uploads with logging
        if ($request->hasFile('nic_photo')) {
            if ($request->file('nic_photo')->isValid()) {
                $application->nic_photo = $request->file('nic_photo')->store($storagePath, 'public');
                Log::info('NIC photo uploaded', ['path' => $application->nic_photo]);
            } else {
                Log::error('Invalid NIC photo upload', ['file' => $request->file('nic_photo')]);
                return redirect()->back()->with('error', 'Invalid NIC photo file.')->withInput();
            }
        }
        if ($request->hasFile('passport_photo')) {
            if ($request->file('passport_photo')->isValid()) {
                $application->passport_photo = $request->file('passport_photo')->store($storagePath, 'public');
                Log::info('Passport photo uploaded', ['path' => $application->passport_photo]);
            } else {
                Log::error('Invalid passport photo upload', ['file' => $request->file('passport_photo')]);
                return redirect()->back()->with('error', 'Invalid passport photo file.')->withInput();
            }
        }
        if ($request->hasFile('photograph')) {
            if ($request->file('photograph')->isValid()) {
                $application->photograph = $request->file('photograph')->store($storagePath, 'public');
                Log::info('Photograph uploaded', ['path' => $application->photograph]);
            } else {
                Log::error('Invalid photograph upload', ['file' => $request->file('photograph')]);
                return redirect()->back()->with('error', 'Invalid photograph file.')->withInput();
            }
        }

        // Merge address fields into a single address string
        $address = trim("{$request->address_line_1}, {$request->address_line_2}, {$request->city}, {$request->province}");

        // Fill application data
        $application->fill([
            'title' => $request->title,
            'full_name' => $request->full_name,
            'name_with_initials' => $request->name_with_initials,
            'birthday' => $request->birthday,
            'nationality' => $request->nationality,
            'nic_number' => $request->nationality === 'Sri Lanka' ? $request->nic_number : null,
            'other_nationality' => $request->nationality === 'Other' ? $request->other_nationality : null,
            'passport_number' => $request->nationality === 'Other' ? $request->passport_number : null,
            'address' => $address,
            'contact_number' => $request->contact_number,
            'whatsapp_number' => $request->whatsapp_number,
            'email_address' => $request->email_address,
            'application_completed' => true,
        ]);

        $application->save();

        Log::info('Application completed', ['user_id' => $user->id, 'folder' => $storagePath]);

        return redirect()->route('course-application.create')->with('status', 'Application submitted successfully!');
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

        // Determine folder name (use existing nic_number or passport_number or fallback)
        $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
        $storagePath = "applications/{$folderName}";
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        // Delete old photograph if exists
        if ($application->photograph) {
            Storage::disk('public')->delete($application->photograph);
        }

        // Store new photograph
        $path = $request->file('photograph')->store($storagePath, 'public');
        $application->photograph = $path;
        $application->save();

        Log::info('Profile photograph updated', ['user_id' => $user->id, 'path' => $path]);

        return response()->json([
            'success' => true,
            'newPhotographUrl' => asset('storage/' . $path)
        ]);
    }
}