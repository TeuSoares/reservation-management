<?php

namespace App\Http\Middleware;

use App\Core\Contracts\Repositories\VerifiedCodeRepositoryInterface;
use App\Support\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(private VerifiedCodeRepositoryInterface $verifiedCodeRepository) {}

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

        if ($request->user()) return $next($request);

        $verificationCode = $request->cookie('verification_code');
        $customer_id = $request->cookie('customer_id');

        if (!$verificationCode || !$customer_id) return HttpResponse::error(['verification_code' => 'Invalid verification code'], 401);

        $verifiedCode = $this->verifiedCodeRepository->checkVerifiedCode($verificationCode, $customer_id);

        if (!$verifiedCode) return HttpResponse::error(['verification_code' => 'Invalid verification code'], 403);

        return $next($request);
    }
}
