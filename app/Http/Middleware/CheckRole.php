<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        // Debug: Check what roles are being passed
        \Log::info('Checking roles:', ['required' => $roles, 'user_role' => $user->role]);

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Allow super_admin to access admin routes
        if (in_array('admin', $roles) && $user->role === 'super_admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized access. Required roles: ' . implode(', ', $roles) . '. Your role: ' . $user->role);
    }
}
