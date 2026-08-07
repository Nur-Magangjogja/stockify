<?php

namespace App\Http\Controllers;

use App\Services\Suppilers\SuppilersService;
use Illuminate\Http\JsonResponse;

class SuppilersController extends Controller
{
    protected $suppilersService;

    public function __construct(SuppilersService $suppilersService)
    {
        $this->suppilersService = $suppilersService;
    }

    public function getSuppliers($suppliersid): JsonResponse
    {
        $supplier = $this->suppilersService->getSuppliers($suppliersid);

        return response()->json([
            'success' => true,
            'code' => 200,
            'data' => $supplier,
        ]);
    }
}
