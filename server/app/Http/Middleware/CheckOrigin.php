<?php

namespace App\Http\Middleware;

use App\Support\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigin = 'http://localhost';
        $origin = $request->headers->get('origin');

        if ($origin !== $allowedOrigin) return HttpResponse::error(['origin' => 'Unauthorized origin'], 403);

        return $next($request);
    }
}
