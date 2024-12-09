<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Contracts\Repositories\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\CustomerRepository;
use App\Core\Contracts\Repositories\VerifiedCodeRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\VerifiedCodeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VerifiedCodeRepositoryInterface::class, VerifiedCodeRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
