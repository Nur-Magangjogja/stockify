<?php

namespace App\Repositories\Products;

use LaravelEasyRepository\Repository;

interface ProductsRepository extends Repository{
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid);
}
