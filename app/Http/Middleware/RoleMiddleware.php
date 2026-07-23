<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user('api') ?? $request->user();
        $userRole = $user?->role?->nama_role;

        if (! $user || ! $userRole) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $allowedRoles = array_map([$this, 'normalizeRole'], $roles);

        if (! in_array($this->normalizeRole($userRole), $allowedRoles, true)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

    private function normalizeRole(string $role): string
    {
        return str($role)->replace('_', '')->lower()->toString();
    }
}
