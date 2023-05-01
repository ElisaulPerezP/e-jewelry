<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VisitsLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $logMessage = sprintf(
            'IP: %s, User Agent: %s, URI: %s, User ID: %s',
            $request->ip(),
            $request->userAgent(),
            $request->getRequestUri(),
            $request->user() ? $request->user()->id : 'Guest'
        );

        Log::channel('visits')->info($logMessage);
        return $next($request);
    }
}
