<?php

namespace App\Services\Report;

use App\Repositories\Products\ProductsRepository;
use App\Services\ActivityLog\ActivityLogService;
use Illuminate\Support\Facades\DB;

class ReportServiceImplement implements ReportService
{
    protected ProductsRepository $productRepository;
    protected ActivityLogService $activityLogService;

    public function __construct(
        ProductsRepository $productRepository,
        ActivityLogService $activityLogService
    ) {
        $this->productRepository  = $productRepository;
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get stock summary report.
     *
     * @param array $filters
     * @return array
     */
    public function getStockSummary(array $filters = []): array
    {
        // Use getLowStock for low filter, otherwise getAllProducts for general report
        // We access data via repository — for complex queries we use DB directly
        $stockExpression = "COALESCE((SELECT SUM(CASE WHEN status = 'Diterima' AND type = 'Masuk' THEN quantity WHEN status = 'Diterima' AND type = 'Keluar' THEN -quantity ELSE 0 END) FROM stock_transactions WHERE stock_transactions.product_id = products.id), 0)";

        $query = \DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select([
                'products.id',
                'products.name',
                'products.sku as code',
                'products.purchase_price as buy_price',
                'products.minimum_stock as min_stock',
                'categories.name as category_name',
            ])
            ->selectRaw("{$stockExpression} as stock")
            ->selectRaw("CASE WHEN {$stockExpression} <= 0 THEN 'Habis' WHEN {$stockExpression} <= products.minimum_stock THEN 'Menipis' ELSE 'Aman' END as stock_status_label")
            ->selectRaw("CASE WHEN {$stockExpression} <= products.minimum_stock THEN 1 ELSE 0 END as is_low_stock")
            ->selectRaw("'pcs' as unit")
            ->selectRaw("({$stockExpression} * products.purchase_price) as stock_value");

        if (!empty($filters['category_id'])) {
            $query->where('products.category_id', $filters['category_id']);
        }

        if (!empty($filters['stock_status'])) {
            match ($filters['stock_status']) {
                'low'   => $query->whereRaw("{$stockExpression} <= products.minimum_stock"),
                'out'   => $query->whereRaw("{$stockExpression} <= 0"),
                default => null,
            };
        }

        $products = $query->get();

        return [
            'products'          => $products,
            'total_products'    => $products->count(),
            'total_stock_value' => $products->sum('stock_value'),
            'low_stock_count'   => $products->where('is_low_stock', 1)->count(),
            'out_of_stock'      => $products->where('stock', '<=', 0)->count(),
        ];
    }

    /**
     * Get transaction report.
     *
     * @param array $filters
     * @return array
     */
    public function getTransactionReport(array $filters = []): array
    {
        $query = \DB::table('stock_transactions')
            ->join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->join('users', 'stock_transactions.user_id', '=', 'users.id')
            ->select(
                'stock_transactions.*',
                'products.name as product_name',
                'products.sku as product_sku',
                'users.name as user_name'
            )
            ->where('stock_transactions.status', 'Diterima')
            ->orderBy('date', 'desc');

        if (!empty($filters['type'])) {
            $query->where('stock_transactions.type', $filters['type'] === 'in' ? 'Masuk' : 'Keluar');
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('date', [$filters['date_from'], $filters['date_to']]);
        }

        if (!empty($filters['product_id'])) {
            $query->where('stock_transactions.product_id', $filters['product_id']);
        }

        $transactions = $query->get();

        return [
            'transactions'  => $transactions,
            'total_in'      => $transactions->where('type', 'Masuk')->count(),
            'total_out'     => $transactions->where('type', 'Keluar')->count(),
            'total_in_qty'  => $transactions->where('type', 'Masuk')->sum('quantity'),
            'total_out_qty' => $transactions->where('type', 'Keluar')->sum('quantity'),
            'total_value'   => 0,
        ];
    }

    /**
     * Get activity report (paginated).
     *
     * @param array $filters
     * @return mixed
     */
    public function getActivityReport(array $filters = [], int $perPage = 20)
    {
        return $this->activityLogService->getActivitiesPaginated($filters, $perPage);
    }

    /**
     * Get dashboard summary data.
     *
     * @return array
     */
    public function getDashboardSummary(): array
    {
        $daysInMonth = now()->daysInMonth;
        $chartData = [
            'labels' => range(1, $daysInMonth),
            'masuk'  => array_fill(0, $daysInMonth, 0),
            'keluar' => array_fill(0, $daysInMonth, 0),
        ];

        $transactions = DB::table('stock_transactions')
            ->selectRaw('DAY(date) as day, type, SUM(quantity) as total')
            ->where('status', 'Diterima')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('day', 'type')
            ->get();

        foreach ($transactions as $t) {
            if ($t->type === 'Masuk') {
                $chartData['masuk'][$t->day - 1] = (int) $t->total;
            } else {
                $chartData['keluar'][$t->day - 1] = (int) $t->total;
            }
        }

        $stockExpression = "COALESCE((SELECT SUM(CASE WHEN status = 'Diterima' AND type = 'Masuk' THEN quantity WHEN status = 'Diterima' AND type = 'Keluar' THEN -quantity ELSE 0 END) FROM stock_transactions WHERE stock_transactions.product_id = products.id), 0)";
        $totalStockValue = DB::table('products')
            ->selectRaw("SUM({$stockExpression} * purchase_price) as total_value")
            ->value('total_value') ?? 0;

        return [
            'total_products'    => \DB::table('products')->count(),
            'total_stock_value' => $totalStockValue,
            'low_stock_count'   => \DB::table('products')->where('minimum_stock', '>', 0)->count(),
            'out_of_stock'      => 0,
            'today_in'          => \DB::table('stock_transactions')->where('status', 'Diterima')->whereDate('date', today())->where('type', 'Masuk')->count(),
            'today_out'         => \DB::table('stock_transactions')->where('status', 'Diterima')->whereDate('date', today())->where('type', 'Keluar')->count(),
            'month_in_qty'      => \DB::table('stock_transactions')->where('status', 'Diterima')->where('type', 'Masuk')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('quantity'),
            'month_out_qty'     => \DB::table('stock_transactions')->where('status', 'Diterima')->where('type', 'Keluar')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('quantity'),
            'stock_by_category' => $this->getStockByCategory(),
            'transaction_chart' => $chartData,
        ];
    }

    /**
     * Get stock aggregated by category.
     *
     * @return array
     */
    private function getStockByCategory(): array
    {
        return DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(products.id) as total_stock'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_stock', 'desc')
            ->limit(7)
            ->get()
            ->toArray();
    }
}
