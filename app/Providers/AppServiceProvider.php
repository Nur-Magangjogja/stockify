<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryImplement;
use App\Services\User\UserService;
use App\Services\User\UserServiceImplement;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Products\ProductsRepositoryImplement;
use App\Services\Products\ProductsService;
use App\Services\Products\ProductsServiceImplement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, UserRepositoryImplement::class);
        $this->app->bind(UserService::class, UserServiceImplement::class);
        $this->app->bind(ProductsRepository::class, ProductsRepositoryImplement::class);
        $this->app->bind(ProductsService::class, ProductsServiceImplement::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
