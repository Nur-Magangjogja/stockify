<?php

namespace App\Services\Products;

use LaravelEasyRepository\BaseService;

interface ProductsService extends BaseService{
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid);
}
