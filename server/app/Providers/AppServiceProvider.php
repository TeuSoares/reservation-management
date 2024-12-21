<?php

namespace App\Providers;

use App\Core\Contracts\Mails\SendVerificationCodeMailInterface;
use Illuminate\Support\ServiceProvider;
use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\CustomerRepository;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Infrastructure\Services\Mails\SendVerificationCodeMail;
use App\Infrastructure\Persistence\Repositories\VerificationCodeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VerificationCodeRepositoryInterface::class, VerificationCodeRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SendVerificationCodeMailInterface::class, SendVerificationCodeMail::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
