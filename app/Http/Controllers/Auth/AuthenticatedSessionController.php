<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\Application;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Validate user type
        if (is_null($user->type)) {
            Log::warning('User type is null during login', ['user_id' => $user->id]);
            return redirect()->route('login')->with('error', 'User type is not set. Please contact support.');
        }

        Log::info('User logged in', ['user_id' => $user->id, 'type' => $user->type]);

        // Check application completion status
        $applicationCompleted = Application::where('user_id', $user->id)->value('application_completed') ?? 0;

        // Determine redirect based on user type and application completion
        $redirect = match ($user->type) {
            'admin' => route('admin.dashboard'),
            'student' => $applicationCompleted ? route('profile.edit') : route('user.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('user.dashboard'),
        };

        return redirect($redirect)->with('status', 'Logged in successfully!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Logged out successfully!');
    }
}