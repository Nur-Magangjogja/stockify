<?php

namespace App\Http\Controllers;
use App\Models\Categories;
use App\Services\Categories\CategoriesService;
use Illuminate\Http\JsonResponse;

class CategoriesController extends Controller
{
    protected $categoriesService;

    public function __construct(CategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }

    public function getCategories($categoriesid): JsonResponse
    {
        $categories = $this->categoriesService->getCategories($categoriesid);

        return response()->json([
            'success' => true,
            'code' => 200,
            'data' => $categories,
        ]);
    }
}
