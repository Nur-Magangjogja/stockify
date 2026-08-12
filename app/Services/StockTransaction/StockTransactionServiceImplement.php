<?php

namespace App\Services\StockTransaction;

use App\Models\Stock_transaction;
use App\Repositories\StockTransaction\StockTransactionRepository;
use App\Repositories\Products\ProductsRepository;
use App\Services\ActivityLog\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransactionServiceImplement implements StockTransactionService
{
    protected StockTransactionRepository $repository;
    protected ProductsRepository $productRepository;
    protected ActivityLogService $activityLogService;

    public function __construct(
        StockTransactionRepository $repository,
        ProductsRepository $productRepository,
        ActivityLogService $activityLogService
    ) {
        $this->repository         = $repository;
        $this->productRepository  = $productRepository;
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get all transactions with filters and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getAllTransactions(array $filters = [], int $perPage = 15)
    {
        return $this->repository->getAll($filters, $perPage);
    }

    /**
     * Get a single transaction by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTransaction(int $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Record a stock-in transaction.
     *
     * @param array $data
    * @return Stock_transaction
     */
    public function recordStockIn(array $data): Stock_transaction
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);

            $transaction = $this->repository->create([
                'product_id'       => $data['product_id'],
                'user_id'          => Auth::id(),
                'type'             => Stock_transaction::TYPE_IN,
                'quantity'         => $data['quantity'],
                'status'           => Stock_transaction::STATUS_PENDING,
                'notes'            => $data['notes'] ?? null,
                'date'             => $data['date'] ?? today(),
            ]);

            $this->activityLogService->log(
                'stock_in',
                'StockTransaction',
                $transaction->id,
                "Barang masuk: {$product->name} sebanyak {$data['quantity']}"
            );

            return $transaction;
        });
    }

    /**
     * Record a stock-out transaction.
     *
     * @param array $data
    * @return Stock_transaction
     */
    public function recordStockOut(array $data): Stock_transaction
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);

            $transaction = $this->repository->create([
                'product_id'       => $data['product_id'],
                'user_id'          => Auth::id(),
                'type'             => Stock_transaction::TYPE_OUT,
                'quantity'         => $data['quantity'],
                'status'           => Stock_transaction::STATUS_PENDING,
                'notes'            => $data['notes'] ?? null,
                'date'             => $data['date'] ?? today(),
            ]);

            $this->activityLogService->log(
                'stock_out',
                'StockTransaction',
                $transaction->id,
                "Barang keluar: {$product->name} sebanyak {$data['quantity']}"
            );

            return $transaction;
        });
    }

    /**
     * Confirm a pending transaction and update product stock.
     *
     * @param int $id
    * @return Stock_transaction
     */
    public function confirmTransaction(int $id): Stock_transaction
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->repository->findById($id);

            if ($transaction->status !== Stock_transaction::STATUS_PENDING) {
                throw new \Exception('Hanya transaksi dengan status menunggu yang dapat dikonfirmasi.');
            }

            // Update transaction status
            $this->repository->updateStatus($id, Stock_transaction::STATUS_CONFIRMED);

            $jenis = $transaction->type === Stock_transaction::TYPE_IN ? 'Barang Masuk' : 'Barang Keluar';
            $this->activityLogService->log(
                'confirm_transaction',
                'StockTransaction',
                $id,
                "Konfirmasi {$jenis}: {$transaction->product->name} sebanyak {$transaction->quantity} " . ($transaction->product->unit ?? 'pcs')
            );

            return $transaction->fresh(['product', 'user']);
        });
    }

    /**
     * Cancel a pending transaction.
     *
     * @param int $id
     * @return bool
     */
    public function cancelTransaction(int $id): bool
    {
        $transaction = $this->repository->findById($id);

        if ($transaction->status === Stock_transaction::STATUS_CONFIRMED) {
            throw new \Exception('Transaksi yang sudah dikonfirmasi tidak dapat dibatalkan.');
        }

        $jenis = $transaction->type === Stock_transaction::TYPE_IN ? 'Barang Masuk' : 'Barang Keluar';
        $this->activityLogService->log(
            'cancel_transaction',
            'StockTransaction',
            $id,
            "Tolak/Batal {$jenis}: {$transaction->product->name} sebanyak {$transaction->quantity} " . ($transaction->product->unit ?? 'pcs')
        );

        return $this->repository->updateStatus($id, Stock_transaction::STATUS_CANCELLED);
    }

    /**
     * Get aggregated dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'today_in'      => $this->repository->getTodayIn(),
            'today_out'     => $this->repository->getTodayOut(),
            'month_in'      => $this->repository->getTotalInThisMonth(),
            'month_out'     => $this->repository->getTotalOutThisMonth(),
            'monthly_chart' => $this->repository->getMonthlyChart(now()->year, now()->month),
        ];
    }

    /**
     * Generate a unique reference number.
     *
     * @param string $type
     * @return string
     */
    private function generateRef(string $type): string
    {
        $prefix = $type === 'IN' ? 'TRX-IN' : 'TRX-OUT';
        return $prefix . '-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
