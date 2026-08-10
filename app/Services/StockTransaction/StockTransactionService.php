<?php

namespace App\Services\StockTransaction;

use App\Models\Stock_transaction;

interface StockTransactionService
{
    /**
     * Get all transactions with filters and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getAllTransactions(array $filters = [], int $perPage = 15);

    /**
     * Get a single transaction by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTransaction(int $id);

    /**
     * Record a stock-in transaction.
     *
     * @param array $data
    * @return Stock_transaction
     */
    public function recordStockIn(array $data): Stock_transaction;

    /**
     * Record a stock-out transaction.
     *
     * @param array $data
    * @return Stock_transaction
     */
    public function recordStockOut(array $data): Stock_transaction;

    /**
     * Confirm a pending transaction and update product stock.
     *
     * @param int $id
    * @return Stock_transaction
     */
    public function confirmTransaction(int $id): Stock_transaction;

    /**
     * Cancel a pending transaction.
     *
     * @param int $id
     * @return bool
     */
    public function cancelTransaction(int $id): bool;

    /**
     * Get aggregated dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array;
}
