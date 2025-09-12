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
    if ($application && $application->application_completed && $application->status !== 'Rejected') {
        return redirect()->route('profile.edit')->with('error', 'You have already submitted an application.');
    }
    $application = $application ?? new Application(['user_id' => $user->id]);

    // Countries array (name and phone code)
    $countries = [
        ['name' => 'Sri Lanka', 'code' => '94'],
        ['name' => 'India', 'code' => '91'],
        ['name' => 'United States', 'code' => '1'],
        ['name' => 'United Kingdom', 'code' => '44'],
        ['name' => 'Australia', 'code' => '61'],
        ['name' => 'Canada', 'code' => '1'],
        // Add more countries as needed
    ];

    return view('application.application', compact('application', 'countries'));
}

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if application already exists
        $application = Application::where('user_id', $user->id)->first();

        if ($application && $application->application_completed && $application->status !== 'Rejected') {
            return redirect()->route('course-application.create')->with('error', 'You have already submitted an application.');
        }

        // Countries array (needed for country code mapping)
        $countries = [
            ['name' => 'Sri Lanka', 'code' => '94'],
            ['name' => 'India', 'code' => '91'],
            ['name' => 'United States', 'code' => '1'],
            ['name' => 'United Kingdom', 'code' => '44'],
            ['name' => 'Australia', 'code' => '61'],
            ['name' => 'Canada', 'code' => '1'],
        ];

        $rules = [
    'title' => 'required|in:Mr,Mrs,Miss,Rev',
    'full_name' => 'required|string|max:255',
    'name_with_initials' => 'required|string|max:255',
    'birthday' => 'required|date|before:today',
    'nationality' => 'required|in:Sri Lanka,Other',

    // Address
    'house_number' => 'nullable|string|max:50',
    'street_name' => 'required|string|max:255',
    'apartment' => 'nullable|string|max:255',
    'district' => 'required|string|max:100',
    'province' => 'required|string|max:100',
    'country' => 'required|string',

    // Phones (+94 format, store only 9 digits after +94)
    'contact_number' => 'required|string|regex:/^[0-9]{6,15}$/',
    'whatsapp_number' => 'nullable|string|regex:/^[0-9]{6,15}$/',
    'home_number' => 'nullable|string|regex:/^[0-9]{6,15}$/',
    'mentor_name' => 'required|string|max:255',
    'email_address' => 'required|email|max:255',
    'photograph' => 'required|image|mimes:jpeg,png,jpg|max:4096',
];


        // Conditional validation based on nationality
        if ($request->nationality === 'Sri Lanka') {
            $rules['nic_number'] = 'required|string|max:12';
            $rules['nic_photo'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:4096';
        } else {
            $rules['other_nationality'] = 'required|string|max:255';
            $rules['passport_number'] = 'required|string|max:20';
            $rules['passport_photo'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:4096';
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Use existing application if it exists, otherwise create new
        $application = $application ?? new Application(['user_id' => $user->id]);

        // Determine folder name based on nationality
        $folderName = $request->nationality === 'Sri Lanka' && $request->nic_number
            ? $request->nic_number
            : ($request->nationality === 'Other' && $request->passport_number
                ? $request->passport_number
                : ($application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time())))); // Fallback: use existing or user_id_timestamp

        // Ensure the folder exists with the applications prefix
        $storagePath = "applications/{$folderName}";
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        // Handle file uploads with logging
        if ($request->hasFile('nic_photo')) {
            if ($request->file('nic_photo')->isValid()) {
                // Delete old NIC photo if exists
                if ($application->nic_photo) {
                    Storage::disk('public')->delete($application->nic_photo);
                }
                $application->nic_photo = $request->file('nic_photo')->store($storagePath, 'public');
                Log::info('NIC photo uploaded', ['path' => $application->nic_photo]);
            } else {
                Log::error('Invalid NIC photo upload', ['file' => $request->file('nic_photo')]);
                return redirect()->back()->with('error', 'Invalid NIC photo file.')->withInput();
            }
        }
        if ($request->hasFile('passport_photo')) {
            if ($request->file('passport_photo')->isValid()) {
                // Delete old passport photo if exists
                if ($application->passport_photo) {
                    Storage::disk('public')->delete($application->passport_photo);
                }
                $application->passport_photo = $request->file('passport_photo')->store($storagePath, 'public');
                Log::info('Passport photo uploaded', ['path' => $application->passport_photo]);
            } else {
                Log::error('Invalid passport photo upload', ['file' => $request->file('passport_photo')]);
                return redirect()->back()->with('error', 'Invalid passport photo file.')->withInput();
            }
        }
        if ($request->hasFile('photograph')) {
            if ($request->file('photograph')->isValid()) {
                // Delete old photograph if exists
                if ($application->photograph) {
                    Storage::disk('public')->delete($application->photograph);
                }
                $application->photograph = $request->file('photograph')->store($storagePath, 'public');
                Log::info('Photograph uploaded', ['path' => $application->photograph]);
            } else {
                Log::error('Invalid photograph upload', ['file' => $request->file('photograph')]);
                return redirect()->back()->with('error', 'Invalid photograph file.')->withInput();
            }
        }

        // Merge address fields into a single string
$addressParts = [
    $request->house_number,  // optional
    $request->street_name,   // required
    $request->apartment,     // optional
    $request->district,      // required
    $request->province,       // required
    $request->country       // required
];

// Filter out empty values
$address = implode(', ', array_filter($addressParts, fn($part) => !empty($part)));

// Add country code to phone numbers
        $countryCode = collect($countries)->firstWhere('name', $request->country)['code'] ?? '94';
        $prefix = '+' . $countryCode;

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
    'contact_number' => $prefix . ltrim($request->contact_number, '0'),
    'whatsapp_number' => $request->whatsapp_number ? $prefix . ltrim($request->whatsapp_number, '0') : null,
    'home_number' => $request->home_number ? $prefix . ltrim($request->home_number, '0') : null,
    'mentor_name' => $request->mentor_name,
    'email_address' => $request->email_address,
    'application_completed' => true,
    'status' => 'Pending', // Reset status to Pending on resubmission
]);

        $application->save();

        Log::info('Application submitted or updated', ['user_id' => $user->id, 'folder' => $storagePath, 'id' => $application->id]);

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

    public function export(Request $request)
{
    $query = Application::select(
        'applications.id',
        'full_name',
        'contact_number',
        'email_address',
        'nic_number',
        'passport_number',
        'nationality',
        'status',
        'updated_by',
        'applications.created_at'
    )->leftJoin('users', 'applications.user_id', '=', 'users.id')->latest('applications.created_at');

    // Apply filters from the request
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nic_number', 'like', "%{$search}%")
              ->orWhere('passport_number', 'like', "%{$search}%")
              ->orWhere('applications.id', 'like', "%{$search}%")
              ->orWhere('email_address', 'like', "%{$search}%");
        });
    }

    if ($request->has('status') && $request->input('status') !== '') {
        $query->where('status', $request->input('status'));
    }

    if ($request->has('updated_by') && $request->input('updated_by') !== '') {
        $query->where('updated_by', $request->input('updated_by'));
    }

    // Apply date range filter
    if ($request->has('date_range')) {
        $now = \Carbon\Carbon::now('Asia/Colombo');
        switch ($request->input('date_range')) {
            case 'last_24h':
                $query->where('applications.created_at', '>=', $now->subHours(24));
                break;
            case 'last_7d':
                $query->where('applications.created_at', '>=', $now->subDays(7));
                break;
            case 'last_month':
                $query->where('applications.created_at', '>=', $now->subMonth());
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();
                    if ($startDate->lte($endDate)) {
                        $query->whereBetween('applications.created_at', [$startDate, $endDate]);
                    }
                }
                break;
        }
    }

    $applications = $query->get();

    // Generate CSV content
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="applications_export_' . date('Ymd_His') . '.csv"',
    ];

    $callback = function () use ($applications) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['ID', 'Full Name', 'Contact Number', 'Email', 'NIC/Passport', 'Nationality', 'Status', 'Updated By', 'Created At']);

        foreach ($applications as $application) {
            $updatedByName = $application->updatedBy ? ($application->updatedBy->name ?? 'N/A') : 'N/A';
            $nicPassport = $application->nationality === 'Sri Lanka' ? ($application->nic_number ?? 'N/A') : ($application->passport_number ?? 'N/A');
            fputcsv($file, [
                sprintf('%.5d', $application->id ?? 0),
                $application->full_name ?? 'N/A',
                $application->contact_number ?? 'N/A',
                $application->email_address ?? 'N/A',
                $nicPassport,
                $application->nationality ?? 'N/A',
                $application->status ?? 'Not Complete',
                $updatedByName,
                $application->created_at ? $application->created_at->format('Y-m-d H:i:s') : 'N/A',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}