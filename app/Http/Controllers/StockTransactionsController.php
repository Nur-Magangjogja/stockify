<?php

namespace App\Http\Controllers;
use App\Models\StockTransactions; 
use Illuminate\Http\Request;

class StockTransactionsController extends Controller
{
    public function index()
    {
        $StockTransactions = StockTransactions::all();

        dd($StockTransactions);
    }
}
