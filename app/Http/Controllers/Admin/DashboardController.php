<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductsService;
use App\Services\StockTransaction\StockTransactionService;
use App\Services\ActivityLog\ActivityLogService;
use App\Services\Report\ReportService;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected ProductsService $productService,
        protected ActivityLogService $activityLogService
    ) {}

    public function index()
    {
        $stats        = $this->reportService->getDashboardSummary();
        $lowStockItems = $this->productService->getLowStockProducts(8);
        $recentActivities = $this->activityLogService->getRecentActivities(10);

        $userStats = [
            'total_users'    => User::count(),
            'total_admin'    => User::byRole('Admin')->count(),
            'total_managers' => User::byRole('Manajer Gudang')->count(),
            'total_staff'    => User::byRole('Staff Gudang')->count(),
        ];

        return view('admin.dashboard.index', compact(
            'stats',
            'lowStockItems',
            'recentActivities',
            'userStats'
        ));
    }
}
