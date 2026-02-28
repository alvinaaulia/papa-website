<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles (e.g. karyawan, pm, hrd, direktur)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $userRole = strtolower((string) ($user->role ?? ''));
        $normalizedRoles = array_map('strtolower', $roles);

        // Normalize common role variants
        $roleMap = [
            'director' => 'direktur',
            'project manager' => 'pm',
            'karyawan' => 'karyawan',
            'hrd' => 'hrd',
            'direktur' => 'direktur',
            'pm' => 'pm',
        ];
        $userRole = $roleMap[$userRole] ?? $userRole;

        if (! in_array($userRole, $normalizedRoles, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. Role tidak diizinkan mengakses fitur ini.',
            ], 403);
        }

        return $next($request);
    }
}
