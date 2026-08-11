<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\Settings\SettingService;
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
use App\Repositories\ActivityLog\ActivityLogRepository;
use App\Repositories\ActivityLog\ActivityLogRepositoryImplement;
use App\Services\ActivityLog\ActivityLogService;
use App\Services\ActivityLog\ActivityLogServiceImplement;
use App\Repositories\StockTransaction\StockTransactionRepository;
use App\Repositories\StockTransaction\StockTransactionRepositoryImplement;
use App\Services\StockTransaction\StockTransactionService;
use App\Services\StockTransaction\StockTransactionServiceImplement;
use App\Services\Report\ReportService;
use App\Services\Report\ReportServiceImplement;

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
        $this->app->bind(SuppilersRepository::class, SuppilersRepositoryImplement::class);
        $this->app->bind(SuppilersService::class, SuppilersServiceImplement::class);
        $this->app->bind(ActivityLogRepository::class, ActivityLogRepositoryImplement::class);
        $this->app->bind(ActivityLogService::class, ActivityLogServiceImplement::class);
        $this->app->bind(StockTransactionRepository::class, StockTransactionRepositoryImplement::class);
        $this->app->bind(StockTransactionService::class, StockTransactionServiceImplement::class);
        $this->app->bind(ReportService::class, ReportServiceImplement::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share App Settings Globally
        try {
            $settingService = app(SettingService::class);
            $settings = $settingService->getSettings();
            
            View::share('appName', $settings['app_name'] ?? 'Stockify');
            View::share('appLogo', $settings['app_logo'] ?? null);
        } catch (\Exception $e) {
            View::share('appName', 'Stockify');
            View::share('appLogo', null);
        }
    }
}
