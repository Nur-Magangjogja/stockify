<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\StockTransaction\StockTransactionService;
use App\Models\Stock_transaction;

class DashboardController extends Controller
{
    public function __construct(protected StockTransactionService $transactionService) {}

    public function index()
    {
        $pendingIn  = Stock_transaction::with('product')
            ->where('type', 'in')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        $pendingOut = Stock_transaction::with('product')
            ->where('type', 'out')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        return view('staff.dashboard.index', compact('pendingIn', 'pendingOut'));
    }
}
