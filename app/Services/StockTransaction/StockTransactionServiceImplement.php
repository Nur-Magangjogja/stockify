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
            $hasPending = Stock_transaction::where('product_id', $data['product_id'])
                ->where('status', Stock_transaction::STATUS_PENDING)
                ->exists();

            if ($hasPending) {
                throw new \Exception('Produk ini masih memiliki transaksi yang belum dikonfirmasi (Pending). Silakan konfirmasi atau batalkan transaksi sebelumnya terlebih dahulu.');
            }

            $product = $this->productRepository->findById($data['product_id']);

            $newSupplierId = array_key_exists('supplier_id', $data) ? $data['supplier_id'] : $product->supplier_id;
            $newSku = !empty($data['reference_number']) ? $data['reference_number'] : $product->sku;

            if ($newSupplierId != $product->supplier_id || $newSku !== $product->sku) {
                // Buat produk baru yang identik tapi dengan supplier & sku berbeda
                $newProductData = [
                    'category_id'    => $product->category_id,
                    'supplier_id'    => $newSupplierId,
                    'name'           => $product->name,
                    'sku'            => $newSku,
                    'description'    => $product->description,
                    'purchase_price' => $product->purchase_price,
                    'selling_price'  => $product->selling_price,
                    'image'          => $product->image,
                    'minimum_stock'  => $product->minimum_stock,
                ];
                $product = $this->productRepository->create($newProductData);
                $data['product_id'] = $product->id;
            }

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
            $hasPending = Stock_transaction::where('product_id', $data['product_id'])
                ->where('status', Stock_transaction::STATUS_PENDING)
                ->exists();

            if ($hasPending) {
                throw new \Exception('Produk ini masih memiliki transaksi yang belum dikonfirmasi (Pending). Silakan konfirmasi atau batalkan transaksi sebelumnya terlebih dahulu.');
            }

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

        $productId = $transaction->product_id;
        $transactionCount = DB::table('stock_transactions')->where('product_id', $productId)->count();

        if ($transactionCount <= 1) {
            // Hapus transaksi dan produknya karena produk ini baru dibuat hanya untuk transaksi ini dan dibatalkan
            $transaction->delete();
            return $this->productRepository->delete($productId);
        }

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

    /**
     * Compress transaction history into Saldo Awal.
     *
     * @param string $mode
     * @param string|array $value
     * @return void
     */
    public function resetTransactionHistory(string $mode, string|array $value): void
    {
        DB::transaction(function () use ($mode, $value) {
            $query = Stock_transaction::query();
            $summaryDate = null;
            $logMessage = '';
            
            if ($mode === 'range') {
                $query->whereBetween('date', [$value[0], $value[1]]);
                $summaryDate = $value[1]; // Put the summary on the end date
                $logMessage = "Kompresi data transaksi dari {$value[0]} hingga {$value[1]} menjadi Saldo Awal.";
            } elseif ($mode === 'month') {
                $parts = explode('-', $value); // YYYY-MM
                $query->whereYear('date', $parts[0])->whereMonth('date', $parts[1]);
                $summaryDate = \Carbon\Carbon::createFromDate($parts[0], $parts[1])->endOfMonth()->toDateString();
                // Avoid using endOfMonth if it's in the future, fallback to today if current month
                if ($summaryDate > today()->toDateString()) {
                    $summaryDate = today()->toDateString();
                }
                $monthName = \Carbon\Carbon::createFromDate($parts[0], $parts[1])->translatedFormat('F Y');
                $logMessage = "Kompresi data transaksi pada bulan $monthName menjadi Saldo Awal.";
            }

            // Menggunakan Chunking (Lazy Loading / pengelompokan data) untuk menghindari kehabisan memory
            // jika data transaksi sangat besar (misal 50.000 transaksi)
            $productSummaries = [];
            (clone $query)->where('status', Stock_transaction::STATUS_CONFIRMED)
                ->chunkById(1000, function ($transactions) use (&$productSummaries) {
                    
                    // Lazy Eager Loading: Memuat relasi product hanya untuk 1000 data ini
                    // untuk mencegah N+1 query jika relasi tersebut diakses di dalam looping
                    $transactions->load('product');

                    foreach ($transactions as $tx) {
                        $pid = $tx->product_id;
                        if (!isset($productSummaries[$pid])) {
                            $productSummaries[$pid] = 0;
                        }
                        
                        if ($tx->type === Stock_transaction::TYPE_IN) {
                            $productSummaries[$pid] += $tx->quantity;
                        } else {
                            $productSummaries[$pid] -= $tx->quantity;
                        }
                    }
                });

            // Delete all matched old transactions
            $query->delete();

            // Insert summaries
            foreach ($productSummaries as $pid => $netQuantity) {
                if ($netQuantity != 0) {
                    Stock_transaction::create([
                        'product_id' => $pid,
                        'user_id' => Auth::id() ?? 1, // Fallback to 1 if no auth (e.g., CLI)
                        'type' => $netQuantity > 0 ? Stock_transaction::TYPE_IN : Stock_transaction::TYPE_OUT,
                        'quantity' => abs($netQuantity),
                        'status' => Stock_transaction::STATUS_CONFIRMED,
                        'notes' => 'Summary Saldo Awal (Reset System)',
                        'date' => $summaryDate
                    ]);
                }
            }
            
            $this->activityLogService->log(
                'reset_transactions',
                'Setting',
                1,
                $logMessage
            );
        });
    }
}
