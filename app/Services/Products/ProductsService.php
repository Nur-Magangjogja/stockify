<?php

namespace App\Services\Products;

use LaravelEasyRepository\BaseService;

interface ProductsService extends BaseService{

    public function getAllProducts(array $filters = [], int $perPage = 15);
    public function getLowStockProducts(int $limit = 8);
    public function getProduct(int $id);
    public function createProduct(array $data, $image = null);
    public function updateProduct(int $id, array $data, $image = null);
    public function deleteProduct(int $id): bool;
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid);
}
