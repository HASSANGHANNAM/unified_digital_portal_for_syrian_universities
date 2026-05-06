<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{

    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->status !== 'active') {
            return response()->json([
                'message' => 'Your account is not active. Please contact support.'
            ], 403);
        }
        return $next($request);
    }
}
