<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection (legacy but still useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Frame protection - prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy (CSP)
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline'; ";
        $csp .= "style-src 'self' 'unsafe-inline'; ";
        $csp .= "img-src 'self' data: https: blob:; ";
        $csp .= "font-src 'self'; ";
        $csp .= "connect-src 'self'; ";
        $csp .= "frame-ancestors 'self'; ";
        $csp .= "base-uri 'self'; ";
        $csp .= "form-action 'self';";

        // Enforce CSP in production, Report-Only in other environments
        $cspHeader = app()->environment('production') ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only';
        $response->headers->set($cspHeader, $csp);

        // Strict Transport Security (HSTS) - only in production with HTTPS
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
