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
            if ($user->status === 'pending') {
                if ($user->hasRole('Student')) {
                    return response()->json([
                        'message' => 'حسابك قيد الانتظار. يرجى الانتظار حتى يتم مراجعة أوراقك من قبل شؤون الطلاب أو قم بملء بياناتك و الانتظار .'
                    ], 403);
                }
                return response()->json([
                    'message' => 'حسابك قيد الانتظار.'
                ], 403);
            } else {
                return response()->json([
                    'message' => 'حسابك غير نشط.'
                ], 403);
            }
        }
        return $next($request);
    }
}
