<?php

namespace App\Http\Middleware;

use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Support\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(private VerificationCodeRepositoryInterface $verifiedCodeRepository) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) return $next($request);

        $verificationCode = $request->cookie('verification_code');
        $customer_id = $request->id;

        if (!$verificationCode || !$customer_id) return HttpResponse::error(['verification_code' => 'Invalid verification code'], 401);

        $verifiedCode = $this->verifiedCodeRepository->checkVerifiedCode($verificationCode, $customer_id);

        if (!$verifiedCode) return HttpResponse::error(['verification_code' => 'Invalid verification code'], 403);

        return $next($request);
    }
}
