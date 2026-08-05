<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->status !== 'active') {
            if ($user->hasRole('Student')) {
                return response()->json([
                    'message' => 'حسابك غير مفعل. يرجى التواصل مع شؤون الطلاب.'
                ], 403);
            }
            return response()->json([
                'message' => 'حسابك غير مفعل.'
            ], 403);
        }

        return $next($request);
    }
}
