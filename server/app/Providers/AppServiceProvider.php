<?php

namespace App\Providers;

use App\Core\Contracts\Repositories\ReservationRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\ReservationRepository;
use App\Core\Contracts\Mails\SendVerificationCodeMailInterface;
use Illuminate\Support\ServiceProvider;
use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\CustomerRepository;
use App\Core\Contracts\Repositories\VerificationCodeRepositoryInterface;
use App\Infrastructure\Services\Mails\SendVerificationCodeMail;
use App\Infrastructure\Persistence\Repositories\VerificationCodeRepository;
use App\Infrastructure\Services\Mails\SendReservationCreated;
use App\Core\Contracts\Mails\SendReservationCreatedInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(VerificationCodeRepositoryInterface::class, VerificationCodeRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SendVerificationCodeMailInterface::class, SendVerificationCodeMail::class);
        $this->app->bind(SendReservationCreatedInterface::class, SendReservationCreated::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
