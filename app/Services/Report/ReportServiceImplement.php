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
    public function getStockSummary(array $filters = [], bool $paginate = true, int $perPage = 20): array
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

        $totalQuery = clone $query;
        $totalProducts = $totalQuery->count();
        $totalStockValue = 0;
        $lowStockCount = 0;
        $outOfStock = 0;
        
        // Unfortunately, selectRaw aliases cannot be used in a simple aggregate like sum() via eloquent cleanly without DB::raw on a subquery.
        // A clean way to calculate overall totals without loading all objects is using a subquery for aggregates:
        $aggregateQuery = \DB::table(\DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->selectRaw("SUM(stock_value) as total_value, SUM(is_low_stock) as low_count, SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) as out_count")
            ->first();

        $totalStockValue = $aggregateQuery->total_value ?? 0;
        $lowStockCount = $aggregateQuery->low_count ?? 0;
        $outOfStock = $aggregateQuery->out_count ?? 0;

        $products = $paginate ? $query->paginate($perPage) : $query->cursor();

        return [
            'products'          => $products,
            'total_products'    => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'low_stock_count'   => $lowStockCount,
            'out_of_stock'      => $outOfStock,
        ];
    }

    /**
     * Get transaction report.
     *
     * @param array $filters
     * @param bool $paginate
     * @param int $perPage
     * @return array
     */
    public function getTransactionReport(array $filters = [], bool $paginate = true, int $perPage = 20): array
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

        $totalInQuery = (clone $query)->where('stock_transactions.type', 'Masuk');
        $totalOutQuery = (clone $query)->where('stock_transactions.type', 'Keluar');

        $totalIn = $totalInQuery->count();
        $totalOut = $totalOutQuery->count();
        $totalInQty = $totalInQuery->sum('quantity');
        $totalOutQty = $totalOutQuery->sum('quantity');

        $transactions = $paginate ? $query->paginate($perPage) : $query->cursor();

        return [
            'transactions'  => $transactions,
            'total_in'      => $totalIn,
            'total_out'     => $totalOut,
            'total_in_qty'  => $totalInQty,
            'total_out_qty' => $totalOutQty,
            'total_value'   => 0,
        ];
    }

    /**
     * Get activity report (paginated).
     *
     * @param array $filters
     * @return mixed
     */
    public function getActivityReport(array $filters = [], int $perPage = 20, bool $paginate = true)
    {
        return $this->activityLogService->getActivitiesPaginated($filters, $perPage, $paginate);
    }

    /**
     * Get dashboard summary data.
     *
     * @return array
     */
    public function getDashboardSummary(?int $month = null, ?int $year = null): array
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        $date = \Carbon\Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        
        $chartData = [
            'labels' => range(1, $daysInMonth),
            'masuk'  => array_fill(0, $daysInMonth, 0),
            'keluar' => array_fill(0, $daysInMonth, 0),
            'period_formatted' => $date->translatedFormat('F Y'),
            'period' => sprintf('%04d-%02d', $year, $month),
        ];

        $transactions = DB::table('stock_transactions')
            ->selectRaw('DAY(date) as day, type, SUM(quantity) as total')
            ->where('status', 'Diterima')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where(function ($query) {
                $query->where('notes', '!=', 'Summary Saldo Awal (Reset System)')
                      ->orWhereNull('notes');
            })
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

        $totalStockQty = DB::table('products')
            ->selectRaw("SUM({$stockExpression}) as total_qty")
            ->value('total_qty') ?? 0;
            
        $totalTransactions = DB::table('stock_transactions')
            ->where('status', 'Diterima')
            ->count();

        // Get all products with their current stock for the new chart
        $productStockChart = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.name as product_name', 'categories.name as category_name', DB::raw("{$stockExpression} as current_stock"))
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get()
            ->toArray();

        return [
            'total_products'    => \DB::table('products')->count(),
            'total_stock_qty'   => $totalStockQty,
            'total_stock_value' => $totalStockValue,
            'total_transactions'=> $totalTransactions,
            'low_stock_count'   => \DB::table('products')->where('minimum_stock', '>', 0)->count(),
            'out_of_stock'      => 0,
            'today_in'          => \DB::table('stock_transactions')->where('status', 'Diterima')->whereDate('date', today())->where('type', 'Masuk')->where(function ($q) { $q->where('notes', '!=', 'Summary Saldo Awal (Reset System)')->orWhereNull('notes'); })->count(),
            'today_out'         => \DB::table('stock_transactions')->where('status', 'Diterima')->whereDate('date', today())->where('type', 'Keluar')->where(function ($q) { $q->where('notes', '!=', 'Summary Saldo Awal (Reset System)')->orWhereNull('notes'); })->count(),
            'month_in_qty'      => \DB::table('stock_transactions')->where('status', 'Diterima')->where('type', 'Masuk')->whereMonth('date', now()->month)->whereYear('date', now()->year)->where(function ($q) { $q->where('notes', '!=', 'Summary Saldo Awal (Reset System)')->orWhereNull('notes'); })->sum('quantity'),
            'month_out_qty'     => \DB::table('stock_transactions')->where('status', 'Diterima')->where('type', 'Keluar')->whereMonth('date', now()->month)->whereYear('date', now()->year)->where(function ($q) { $q->where('notes', '!=', 'Summary Saldo Awal (Reset System)')->orWhereNull('notes'); })->sum('quantity'),
            'stock_by_category' => $this->getStockByCategory(),
            'transaction_chart' => $chartData,
            'product_stock_chart' => $productStockChart,
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
            ->get()
            ->toArray();
    }
}
