<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Services\Products\ProductsService;
use App\Services\StockTransaction\StockTransactionService;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected ProductsService $productService,
        protected StockTransactionService $transactionService
    ) {}

    public function index()
    {
        $stats        = $this->transactionService->getDashboardStats();
        $lowStockItems = $this->productService->getLowStockProducts(8);
        $summary      = $this->reportService->getDashboardSummary();

        return view('manager.dashboard.index', compact('stats', 'lowStockItems', 'summary'));
    }
}
