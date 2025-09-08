<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CourseApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Application;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If user is admin, bypass course application check
        if ($user->type === 'admin') {
            return view('frontend.profile', [
                'user' => $user,
            ]);
        }

        return view('frontend.profile', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function exportApplication()
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();
        $courseApplication = CourseApplication::where('user_id', $user->id)->first();
        $student = Student::where('user_id', $user->id)->first();

        if (!$application) {
            return redirect()->back()->with('error', 'No application found for your account.');
        }

        // Prepare data for the template
        $data = [
            'student_id' => $student ? $student->student_id : 'N/A',
            'batch_no' => $courseApplication && $courseApplication->course ? $courseApplication->course->batches->first()->batch_no ?? 'N/A' : 'N/A',
            'photograph' => $application->photograph ?? 'N/A',
            'course_name' => $courseApplication ? $courseApplication->course_name : 'N/A',
            'name_with_initials' => $application->name_with_initials ?? 'N/A',
            'address' => $application->address ?? 'N/A',
            'email' => $application->email ?? $user->email ?? 'N/A',
            'birthday' => $application->birthday ? $application->birthday->format('F j, Y') : 'N/A',
            'nic_number' => $application->nic_number ?? $application->passport_number ?? 'N/A',
            'contact_number' => $application->contact_number ?? 'N/A',
            'whatsapp_number' => $application->whatsapp_number ?? 'N/A',
        ];

        // Load the HTML template and replace placeholders
        $html = file_get_contents(resource_path('views/pdf/application_form_template.blade.php')); // Create this file
        foreach ($data as $key => $value) {
            $html = str_replace('{' . $key . '}', $value, $html);
        }

        // Generate PDF
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        // Download the PDF
        return $pdf->download('application_form_' . $user->id . '_' . date('Ymd_His') . '.pdf');
    }
}