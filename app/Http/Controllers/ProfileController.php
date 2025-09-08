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
use Carbon\Carbon;
 
class ProfileController extends Controller
{
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();
 
        if ($user->type === 'admin') {
            return view('frontend.profile', ['user' => $user]);
        }
 
        return view('frontend.profile', ['user' => $user]);
    }
 
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
 
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
 
        $request->user()->save();
 
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
 
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
        $application       = Application::where('user_id', $user->id)->first();
        $courseApplication = CourseApplication::with('course.batches')->where('user_id', $user->id)->first();
        $student           = Student::where('user_id', $user->id)->first();
 
        if (!$application) {
            return back()->with('error', 'No application found for your account.');
        }
 
        // Resolve batch no safely
        $batchNo = 'N/A';
        if ($courseApplication && $courseApplication->course && $courseApplication->course->batches->count()) {
            $batchNo = optional($courseApplication->course->batches->first())->batch_no ?? 'N/A';
        }
 
        // Absolute paths for images
        $logoAbsPath  = public_path('frontend/assets/images/ec_logo.webp'); // Prefer PNG/JPG for DomPDF reliability
        $photoAbsPath = null;
 
        // If your photograph is stored under /storage (after `php artisan storage:link`)
        if (!empty($application->photograph)) {
            // If it's an absolute URL, keep it (DomPDF can load remote only if isRemoteEnabled = true)
            if (preg_match('#^https?://#i', $application->photograph)) {
                $photoAbsPath = $application->photograph;
            } else {
                // Try public path first, then storage
                $candidate = public_path(ltrim($application->photograph, '/'));
                if (!file_exists($candidate)) {
                    $candidate = public_path('storage/' . ltrim($application->photograph, '/'));
                }
                if (file_exists($candidate)) {
                    $photoAbsPath = $candidate;
                }
            }
        }
 
        $data = [
            'student_id'        => $student->student_id ?? 'N/A',
            'batch_no'          => $batchNo,
            'course_name'       => $courseApplication->course_name ?? 'N/A',
            'name_with_initials'=> $application->name_with_initials ?? 'N/A',
            'address'           => $application->address ?? 'N/A',
            'email'             => $application->email ?? $user->email ?? 'N/A',
            'birthday'          => $application->birthday ? Carbon::parse($application->birthday)->format('F j, Y') : 'N/A',
            'nic_number'        => $application->nic_number ?? $application->passport_number ?? 'N/A',
            'contact_number'    => $application->contact_number ?? 'N/A',
            'whatsapp_number'   => $application->whatsapp_number ?? 'N/A',
 
            // Images as base64 for guaranteed embedding
            'logo_base64'       => $this->imageToBase64($logoAbsPath),
            'photo_base64'      => $this->imageToBase64($photoAbsPath),
        ];
 
        $pdf = Pdf::loadView('pdf.application_form_template', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true, // allows http(s) images if needed
                'dpi'                  => 96,
            ]);
 
        return $pdf->download('application_form_' . $user->id . '_' . date('Ymd_His') . '.pdf');
    }
 
    private function imageToBase64(?string $pathOrUrl): ?string
    {
        if (!$pathOrUrl) return null;
 
        // Remote URL
        if (preg_match('#^https?://#i', $pathOrUrl)) {
            try {
                $data = @file_get_contents($pathOrUrl);
                if ($data === false) return null;
                $type = pathinfo(parse_url($pathOrUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            } catch (\Throwable $e) {
                return null;
            }
        }
 
        // Local file
        if (!file_exists($pathOrUrl)) return null;
        $type = pathinfo($pathOrUrl, PATHINFO_EXTENSION) ?: 'png';
        $data = @file_get_contents($pathOrUrl);
        if ($data === false) return null;
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}