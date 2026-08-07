<?php

namespace App\Services\Suppilers;

use Illuminate\Support\Facades\Log;
use LaravelEasyRepository\Service;
use App\Repositories\Suppilers\SuppilersRepository;

class SuppilersServiceImplement extends Service implements SuppilersService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(SuppilersRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid)
    {
        try {
            return $this->mainRepository->getSuppliers($suppliersid);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return null;
        }
    }
}
