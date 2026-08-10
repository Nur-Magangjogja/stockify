<?php

namespace App\Repositories\StockTransaction;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Stock_transaction;

class StockTransactionRepositoryImplement extends Eloquent implements StockTransactionRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Stock_transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Get all stock transactions with filters and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->with(['product.stockTransactions', 'user'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type'] === 'in' ? 'Masuk' : 'Keluar');
        }

        if (!empty($filters['status'])) {
            $status = ['pending' => 'Pending', 'confirmed' => 'Diterima', 'cancelled' => 'Ditolak'][$filters['status']] ?? $filters['status'];
            $query->where('status', $status);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->dateRange($filters['date_from'], $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('notes', 'like', "%{$filters['search']}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$filters['search']}%"));
            });
        }

        return $query->paginate($perPage)->appends($filters);
    }

    /**
     * Find a stock transaction by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return $this->model->with(['product', 'user'])->findOrFail($id);
    }

    /**
     * Create a new stock transaction.
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update the status of a stock transaction.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->model->where('id', $id)->update(['status' => $status]);
    }

    /**
     * Get total confirmed inbound transactions today.
     *
     * @return int
     */
    public function getTodayIn(): int
    {
        return $this->model->today()->in()->confirmed()->count();
    }

    /**
     * Get total confirmed outbound transactions today.
     *
     * @return int
     */
    public function getTodayOut(): int
    {
        return $this->model->today()->out()->confirmed()->count();
    }

    /**
     * Get monthly chart data grouped by day and type.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlyChart(int $year, int $month): array
    {
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = ['in' => array_fill(0, $days, 0), 'out' => array_fill(0, $days, 0)];

        $transactions = $this->model
            ->selectRaw('DAY(date) as day, type, SUM(quantity) as total')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->confirmed()
            ->groupBy('day', 'type')
            ->get();

        foreach ($transactions as $tx) {
            $data[$tx->type][$tx->day - 1] = $tx->total;
        }

        return $data;
    }

    /**
     * Get transactions by product with pagination.
     *
     * @param int $productId
     * @param int $perPage
     * @return mixed
     */
    public function getByProduct(int $productId, int $perPage = 15)
    {
        return $this->model->with(['user'])
            ->where('product_id', $productId)
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get total inbound quantity this month.
     *
     * @return int
     */
    public function getTotalInThisMonth(): int
    {
        return $this->model->in()->confirmed()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('quantity');
    }

    /**
     * Get total outbound quantity this month.
     *
     * @return int
     */
    public function getTotalOutThisMonth(): int
    {
        return $this->model->out()->confirmed()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('quantity');
    }
}
