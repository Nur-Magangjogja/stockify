<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\StockTransaction\StockTransactionService;
use App\Services\Products\ProductsService;
use App\Services\Suppilers\SuppilersService;
use App\Models\Products;
use App\Models\Stock_transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(
        protected StockTransactionService $transactionService,
        protected ProductsService $productService,
        protected SuppilersService $supplierService,
        protected \App\Services\ActivityLog\ActivityLogService $activityLogService
    ) {}

    public function index(Request $request)
    {
        $filters      = $request->only(['type', 'status', 'date_from', 'date_to', 'search']);
        $transactions = $this->transactionService->getAllTransactions($filters, 15);
        return view('manager.stocks.index', compact('transactions', 'filters'));
    }

    public function createIn()
    {
        $products  = $this->productService->getAllProducts([], 9999);
        $suppliers = $this->supplierService->getAllSuppliers(true);
        return view('manager.stocks.create-in', compact('products', 'suppliers'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'notes'            => 'nullable|string',
            'date'             => 'required|date',
        ]);

        try {
            $tx = $this->transactionService->recordStockIn($validated);
            return redirect()->route('manager.stocks.index')
                ->with('success', "Barang masuk <strong>#{$tx->id}</strong> berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function createOut()
    {
        $products = $this->productService->getAllProducts([], 9999);
        return view('manager.stocks.create-out', compact('products'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'notes'            => 'nullable|string',
            'date'             => 'required|date',
        ]);

        try {
            $tx = $this->transactionService->recordStockOut($validated);
            return redirect()->route('manager.stocks.index')
                ->with('success', "Barang keluar <strong>#{$tx->id}</strong> berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function confirm(int $id)
    {
        try {
            $this->transactionService->confirmTransaction($id);
            return back()->with('success', 'Transaksi dikonfirmasi. Stok diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Opname menggunakan tabel products dan stock_transactions yang sudah ada.
    public function opnameIndex()
    {
        $products = Products::with('stockTransactions')->orderBy('name')->get();
        return view('manager.stocks.opname.index', compact('products'));
    }

    public function opnameStore(Request $request)
    {
        $validated = $request->validate([
            'opname_date' => 'required|date',
            'notes'       => 'nullable|string',
            'stocks'      => 'required|array',
            'stocks.*'    => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['stocks'] as $productId => $actualStock) {
                $product = Products::with('stockTransactions')->findOrFail($productId);
                $difference = $actualStock - (int) $product->stock;

                if ($difference === 0) {
                    continue;
                }

                Stock_transaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => $difference > 0 ? Stock_transaction::TYPE_IN : Stock_transaction::TYPE_OUT,
                    'quantity' => abs($difference),
                    'date' => $validated['opname_date'],
                    'status' => Stock_transaction::STATUS_CONFIRMED,
                    'notes' => $validated['notes'] ?: 'Penyesuaian stock opname',
                ]);
            }
            
            $notesLog = !empty($validated['notes']) ? ' - Catatan: ' . $validated['notes'] : '';
            $this->activityLogService->log(
                action: 'STOCK_OPNAME',
                description: 'Stock Opname tanggal ' . $validated['opname_date'] . $notesLog
            );
        });

        return redirect()->route('manager.stocks.opname.index')
            ->with('success', 'Stock opname berhasil disimpan dan stok telah disesuaikan.');
    }
}
