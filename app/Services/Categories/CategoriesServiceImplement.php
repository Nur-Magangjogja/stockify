<?php

namespace App\Services\Categories;

use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Categories\CategoriesRepository;

class CategoriesServiceImplement extends Service implements CategoriesService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(CategoriesRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
    
    /**
     * getCategories
     *
     * @param $categoriesid
     * @return mixed
     */
    public function getCategories($categoriesid)
    {
        try {
            return $this->mainRepository->getCategories($categoriesid);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return null;
        }
    }
}
