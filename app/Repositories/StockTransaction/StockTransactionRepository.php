<?php

namespace App\Repositories\StockTransaction;

use LaravelEasyRepository\Repository;

interface StockTransactionRepository extends Repository
{
    /**
     * Get all stock transactions with filters and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getAll(array $filters = [], int $perPage = 15);

    /**
     * Find a stock transaction by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id);

    /**
     * Update the status of a stock transaction.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Get total confirmed inbound transactions today.
     *
     * @return int
     */
    public function getTodayIn(): int;

    /**
     * Get total confirmed outbound transactions today.
     *
     * @return int
     */
    public function getTodayOut(): int;

    /**
     * Get monthly chart data grouped by day and type.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlyChart(int $year, int $month): array;

    /**
     * Get transactions by product with pagination.
     *
     * @param int $productId
     * @param int $perPage
     * @return mixed
     */
    public function getByProduct(int $productId, int $perPage = 15);

    /**
     * Get total inbound quantity this month.
     *
     * @return int
     */
    public function getTotalInThisMonth(): int;

    /**
     * Get total outbound quantity this month.
     *
     * @return int
     */
    public function getTotalOutThisMonth(): int;
}
