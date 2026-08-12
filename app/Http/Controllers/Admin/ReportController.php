<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Services\Categories\CategoriesService;
use App\Services\Products\ProductsService;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected CategoriesService $categoryService,
        protected ProductsService $productService
    ) {}

    public function index()
    {
        return view('admin.reports.index');
    }

    public function stockReport(Request $request)
    {
        $filters    = $request->only(['category_id', 'stock_status']);
        $categories = $this->categoryService->getAllCategories(true);
        $report     = $this->reportService->getStockSummary($filters);

        if ($request->get('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StockExport($report), 'laporan-stok-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.stock', compact('report', 'categories', 'filters'));
    }

    public function transactionReport(Request $request)
    {
        $filters = $request->only(['type', 'date_from', 'date_to', 'product_id']);
        $report  = $this->reportService->getTransactionReport($filters);

        if ($request->get('export') === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TransactionExport($report), 'laporan-transaksi-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.transactions', compact('report', 'filters'));
    }

    public function activityReport(Request $request)
    {
        $filters    = $request->only(['user_id', 'action', 'date_from', 'date_to']);
        // Remove pagination for export to get all data, but getActivityReport uses getActivitiesPaginated which always paginates.
        // We will just fetch everything if exporting, or use the paginator's items.
        // For simplicity, we'll export what is fetched, but getActivitiesPaginated accepts limit.
        if ($request->get('export') === 'excel') {
            $activities = $this->reportService->getActivityReport($filters, 10000)->items();
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ActivityExport($activities), 'laporan-aktivitas-' . date('Y-m-d') . '.xlsx');
        }

        $activities = $this->reportService->getActivityReport($filters);
        $users      = User::orderBy('name')->get();

        return view('admin.reports.activities', compact('activities', 'filters', 'users'));
    }
}
