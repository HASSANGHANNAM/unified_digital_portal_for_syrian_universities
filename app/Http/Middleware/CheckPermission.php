<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!auth()->user()->hasPermissionTo($permission)) {
            return response()->json([
                'status' => 0,
                'message' => 'Access denied. You do not have the required permission to perform this action.',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }
}
