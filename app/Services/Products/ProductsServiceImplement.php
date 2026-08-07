<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Products\ProductsRepository;

class ProductsServiceImplement extends Service implements ProductsService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(ProductsRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
    
    /**
     * getProducts
     *
     * @param $productid
     * @return mixed
     */
    public function getProducts($productid)
    {
      try {
        return $this->mainRepository->getProducts($productid);
      } catch (\Exception $exception) {
        Log::debug($exception->getMessage());
        return [];
      }
    }
}
