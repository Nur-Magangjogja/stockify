<?php

namespace App\Repositories\Products;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Products;

class ProductsRepositoryImplement extends Eloquent implements ProductsRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Products $model)
    {
        $this->model = $model;
    }
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid)
    {
        return $this->model->where('id', $productid)->first();
    }
}
