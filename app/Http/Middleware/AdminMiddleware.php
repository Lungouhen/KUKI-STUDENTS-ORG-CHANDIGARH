<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login with admin credentials.');
        }

        $user = Auth::user();

        if ($user->is_admin) {
            return $next($request);
        }

        if (!empty($roles) && in_array($user->role, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access this module.');
    }
}
