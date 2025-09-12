<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictType
{
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user's type is in the allowed list
        if (!in_array($user->type, $types)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
