<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()?->email_verified_at) {
            return response()->json(['message' => 'حسابك غير مفعل يرجى تأكيد حسابك اولاً'], 405);
        }

        return $next($request);
    }
}
