<?php

namespace App\Repositories\Categories;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Categories;

class CategoriesRepositoryImplement extends Eloquent implements CategoriesRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Categories $model)
    {
        $this->model = $model;
    }
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories($categoriesid)
    {
        return $this->model->find($categoriesid);
    }
}
