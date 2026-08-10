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

    public function findById(int $id): Products
    {
        return $this->model->findOrFail($id);
    }

    public function updateStock(int $id, int $stock): bool
    {
        return (bool) $this->model->whereKey($id)->update(['stock' => $stock]);
    }
}
