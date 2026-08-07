<?php

namespace App\Http\Controllers;
use App\Models\Products;
use App\Services\Products\ProductsService;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{
    protected $productsService;

    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    public function getProducts($productid): JsonResponse
    {
        $product = $this->productsService->getProducts($productid);

        return response()->json([
            'success' => true,
            'code' => 200,
            'data' => $product,
        ]);
    }
}
