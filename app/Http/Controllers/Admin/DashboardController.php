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

    public function index(\Illuminate\Http\Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));
        $parts = explode('-', $period);
        $year = $parts[0] ?? now()->year;
        $month = $parts[1] ?? now()->month;

        $stats        = $this->reportService->getDashboardSummary((int) $month, (int) $year);
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
