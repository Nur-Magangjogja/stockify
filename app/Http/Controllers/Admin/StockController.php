<?php

namespace App\Http\Controllers\Admin;

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
        $filters      = $request->only(['type', 'status', 'date_from', 'date_to', 'search', 'product_id']);
        $transactions = $this->transactionService->getAllTransactions($filters, 15);
        return view('admin.stocks.index', compact('transactions', 'filters'));
    }

    public function createIn()
    {
        $products  = $this->productService->getAllProducts([], 9999);
        $suppliers = $this->supplierService->getAllSuppliers(true);
        return view('admin.stocks.create-in', compact('products', 'suppliers'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'notes'            => 'nullable|string',
            'date'             => 'required|date',
            'supplier_id'      => 'nullable|exists:suppliers,id',
            'reference_number' => 'nullable|string|max:100|unique:products,sku,' . $request->product_id,
        ]);

        $product = \App\Models\Products::find($request->product_id);
        $newSupplierId = array_key_exists('supplier_id', $validated) ? $validated['supplier_id'] : $product->supplier_id;
        $newSku = !empty($validated['reference_number']) ? $validated['reference_number'] : $product->sku;

        if ($newSupplierId != $product->supplier_id && $newSku === $product->sku) {
            return back()->withInput()->with('error', 'Peringatan: Jika Anda mengganti Supplier, Anda <b>wajib mengganti No. Referensi / SKU</b> dengan kode yang baru (tidak boleh sama dengan produk sebelumnya).');
        }

        try {
            $transaction = $this->transactionService->recordStockIn($validated);
            return redirect()->route('admin.stocks.index')
                ->with('success', "Transaksi barang masuk <strong>#{$transaction->id}</strong> berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function createOut()
    {
        $products = $this->productService->getAllProducts([], 9999);
        return view('admin.stocks.create-out', compact('products'));
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
            $transaction = $this->transactionService->recordStockOut($validated);
            return redirect()->route('admin.stocks.index')
                ->with('success', "Transaksi barang keluar <strong>#{$transaction->id}</strong> berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function confirm(int $id)
    {
        try {
            $this->transactionService->confirmTransaction($id);
            return back()->with('success', 'Transaksi berhasil dikonfirmasi. Stok telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(int $id)
    {
        try {
            $this->transactionService->cancelTransaction($id);
            return back()->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Stock Opname menggunakan tabel products dan stock_transactions yang sudah ada.
    public function opnameIndex(Request $request)
    {
        $query = Products::with('stockTransactions')->orderBy('name');
        
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->paginate(15)->appends($request->only('search'));
        
        return view('admin.stocks.opname.index', compact('products'));
    }

    public function opnameStore(Request $request)
    {
        $validated = $request->validate([
            'opname_date' => 'required|date',
            'notes'       => 'required|string',
            'stocks'      => 'required|array',
            'stocks.*'    => 'required|integer|min:0|max:4294967295',
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

        return redirect()->route('admin.stocks.opname.index')
            ->with('success', 'Stock opname berhasil disimpan dan stok telah disesuaikan.');
    }
}
