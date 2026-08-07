<?php

namespace App\Repositories\Categories;

use LaravelEasyRepository\Repository;

interface CategoriesRepository extends Repository{
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories($categoriesid);
}