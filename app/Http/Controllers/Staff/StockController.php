<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\StockTransaction\StockTransactionService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(protected StockTransactionService $transactionService) {}

    public function confirmIn(int $id)
    {
        try {
            $this->transactionService->confirmTransaction($id);
            return back()->with('success', 'Penerimaan barang berhasil dikonfirmasi. Stok telah ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirmOut(int $id)
    {
        try {
            $this->transactionService->confirmTransaction($id);
            return back()->with('success', 'Pengeluaran barang berhasil dikonfirmasi. Stok telah dikurangkan.');
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

    public function index(Request $request)
    {
        $filters      = $request->only(['type', 'status', 'date_from', 'date_to', 'search', 'product_id']);
        $transactions = $this->transactionService->getAllTransactions($filters, 15);
        return view('admin.stocks.index', compact('transactions', 'filters'));
    }

    public function opnameIndex()
    {
        $products = \App\Models\Products::with('stockTransactions')->orderBy('name')->get();
        return view('admin.stocks.opname.index', compact('products'));
    }
}
