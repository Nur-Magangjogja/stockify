<?php

namespace App\Repositories\Products;

use LaravelEasyRepository\Repository;
use App\Models\Products;

interface ProductsRepository extends Repository{

    public function findById(int $id): Products;
    public function updateStock(int $id, int $stock): bool;
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid);
}
