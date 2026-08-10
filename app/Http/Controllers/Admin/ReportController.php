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

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.stock', compact('report', 'filters'));
            return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.reports.stock', compact('report', 'categories', 'filters'));
    }

    public function transactionReport(Request $request)
    {
        $filters = $request->only(['type', 'date_from', 'date_to', 'product_id']);
        $report  = $this->reportService->getTransactionReport($filters);

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.transactions', compact('report', 'filters'));
            return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.reports.transactions', compact('report', 'filters'));
    }

    public function activityReport(Request $request)
    {
        $filters    = $request->only(['user_id', 'action', 'date_from', 'date_to']);
        $activities = $this->reportService->getActivityReport($filters);
        $users      = User::orderBy('name')->get();

        return view('admin.reports.activities', compact('activities', 'filters', 'users'));
    }
}
