<?php

namespace App\Http\Controllers;

use App\Services\Categories\CategoriesService;

class CategoriesController extends Controller
{
    public function __construct(protected CategoriesService $categoriesService) {}

    public function getCategories(int $categoriesid)
    {
        $category = $this->categoriesService->getCategories($categoriesid);

        if ($category === null) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json($category);
    }
}