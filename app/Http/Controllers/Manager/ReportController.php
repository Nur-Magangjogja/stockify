<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Services\Categories\CategoriesService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected CategoriesService $categoryService
    ) {}

    public function index()
    {
        return view('manager.reports.index');
    }

    public function stockReport(Request $request)
    {
        $filters    = $request->only(['category_id', 'stock_status']);
        $categories = $this->categoryService->getAllCategories(true);
        $report     = $this->reportService->getStockSummary($filters);

        if ($request->get('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StockExport($report), 'laporan-stok-manager-' . date('Y-m-d') . '.xlsx');
        }

        return view('manager.reports.stock', compact('report', 'categories', 'filters'));
    }

    public function transactionReport(Request $request)
    {
        $filters = $request->only(['type', 'date_from', 'date_to', 'product_id']);
        $report  = $this->reportService->getTransactionReport($filters);

        if ($request->get('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TransactionExport($report), 'laporan-transaksi-manager-' . date('Y-m-d') . '.xlsx');
        }

        return view('manager.reports.transactions', compact('report', 'filters'));
    }

    public function activityReport(Request $request)
    {
        $filters    = $request->only(['user_id', 'action', 'date_from', 'date_to']);
        
        if ($request->get('export') === 'excel') {
            $activities = $this->reportService->getActivityReport($filters, 10000)->items();
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ActivityExport($activities), 'laporan-aktivitas-manager-' . date('Y-m-d') . '.xlsx');
        }

        $activities = $this->reportService->getActivityReport($filters);
        $users      = \App\Models\User::orderBy('name')->get();

        return view('manager.reports.activities', compact('activities', 'filters', 'users'));
    }
}
