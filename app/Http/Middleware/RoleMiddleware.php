<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The required role value
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check if the user's role matches the required role
        if ($user->role->value === $role) {
            return $next($request);
        }

        // Redirect to appropriate dashboard based on user's actual role
        if ($user->role === UserRole::Teacher) {
            return redirect()->route('teacher.index')->with('error', 'Unauthorized access');
        } elseif ($user->role === UserRole::Student) {
            return redirect()->route('student.dashboard')->with('error', 'Unauthorized access');
        }

        // Fallback redirect
        return redirect()->route('login')->with('error', 'Unauthorized access');
    }
}
