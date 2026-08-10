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
}
