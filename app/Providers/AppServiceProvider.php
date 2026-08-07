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
use App\Repositories\Categories\CategoriesRepository;
use App\Repositories\Categories\CategoriesRepositoryImplement;
use App\Services\Categories\CategoriesService;
use App\Services\Categories\CategoriesServiceImplement;
use App\Repositories\Suppilers\SuppilersRepository;
use App\Repositories\Suppilers\SuppilersRepositoryImplement;
use App\Services\Suppilers\SuppilersService;
use App\Services\Suppilers\SuppilersServiceImplement;

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
        $this->app->bind(CategoriesRepository::class, CategoriesRepositoryImplement::class);
        $this->app->bind(CategoriesService::class, CategoriesServiceImplement::class);
        $this->app->bind(SuppliersRepository::class, SuppliersRepositoryImplement::class);
        $this->app->bind(SuppliersService::class, SuppliersServiceImplement::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
