<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RestrictByUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        if (Auth::check()) {
            $userType = Auth::user()->type ?? 'user';
            Log::info('Checking user type', ['user_id' => Auth::user()->id, 'type' => $userType, 'allowed_types' => $types]);
            if (in_array($userType, $types)) {
                return $next($request);
            }
        }

        Log::warning('Unauthorized access attempt', ['user_id' => Auth::user()->id ?? null]);
        return redirect()->route('user.dashboard')->with('error', 'Unauthorized access.');
    }
}