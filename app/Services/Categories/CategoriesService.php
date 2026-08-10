<?php

namespace App\Services\Categories;

use LaravelEasyRepository\BaseService;

interface CategoriesService extends BaseService{

    public function getAllCategories(bool $activeOnly = false);
    public function getAllCategoriesPaginated(int $perPage = 15);
    public function getCategory(int $id);
    public function createCategory(array $data);
    public function updateCategory(int $id, array $data);
    public function deleteCategory(int $id): bool;
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories(int $categoriesid);
}
