<?php

namespace App\Repositories\Categories;

use LaravelEasyRepository\Repository;
use App\Models\Categories;

interface CategoriesRepository extends Repository{
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories(int $categoriesid): ?Categories;
}