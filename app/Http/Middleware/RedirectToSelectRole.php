<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToSelectRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // For AJAX requests, return JSON with redirect URL
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'redirect' => route('select-role')
                ], 401);
            }

            return redirect()->route('select-role');
        }

        return $next($request);
    }
}
