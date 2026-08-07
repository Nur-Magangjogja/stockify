<?php

namespace App\Services\Categories;

use LaravelEasyRepository\BaseService;

interface CategoriesService extends BaseService{
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories($categoriesid);
}
