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
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
            }

            return redirect()->route('login-page');
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
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden. Role tidak diizinkan mengakses fitur ini.',
                ], 403);
            }

            $equivalentUrl = $this->getEquivalentUrlForRole($request->path(), $userRole);
            if ($equivalentUrl !== null) {
                return redirect()->to($equivalentUrl);
            }

            return redirect()->to($this->getDashboardUrlForRole($userRole));
        }

        return $next($request);
    }

    /**
     * Redirect ke halaman setara untuk role user (mis. /pm/presence → /karyawan/presence).
     */
    private function getEquivalentUrlForRole(string $path, string $userRole): ?string
    {
        $segments = explode('/', trim($path, '/'));
        if (count($segments) < 2) {
            return null;
        }

        $rolePrefix = $this->getRolePrefix($userRole);
        $firstSegment = $segments[1];
        $rest = array_slice($segments, 2);

        $mappedSegment = $this->mapPathSegmentForRole($firstSegment, $userRole);
        $newPath = $rolePrefix.'/'.$mappedSegment.($rest ? '/'.implode('/', $rest) : '');

        return url($newPath);
    }

    private function getRolePrefix(string $role): string
    {
        return match ($role) {
            'karyawan' => 'karyawan',
            'pm' => 'pm',
            'hrd' => 'hrd',
            'direktur' => 'director',
            default => 'karyawan',
        };
    }

    /**
     * Map path segment (mis. dashboard-PM) ke segment yang benar untuk role user.
     */
    private function mapPathSegmentForRole(string $segment, string $userRole): string
    {
        $dashboardSegments = [
            'dashboard' => ['karyawan' => 'dashboard', 'pm' => 'dashboard', 'hrd' => 'dashboard', 'direktur' => 'dashboard'],
        ];

        $lower = strtolower($segment);
        foreach ($dashboardSegments as $key => $map) {
            if (strtolower($key) === $lower && isset($map[$userRole])) {
                return $map[$userRole];
            }
        }

        return $segment;
    }

    /**
     * Fallback: URL dashboard untuk role (jika halaman setara tidak ada).
     */
    private function getDashboardUrlForRole(string $role): string
    {
        return match ($role) {
            'karyawan' => route('dashboard-employee'),
            'pm' => route('dashboard-PM'),
            'hrd' => route('dashboard-hrd'),
            'direktur' => route('dashboard-director'),
            default => url('/'),
        };
    }
}
