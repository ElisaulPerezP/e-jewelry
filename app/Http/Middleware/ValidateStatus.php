<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ValidateStatus
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if (auth()->check() && !auth()->user()->status) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(trans('auth.disable'));
        }

        return $next($request);
    }
}
