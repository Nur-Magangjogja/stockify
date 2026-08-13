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

    public function getAllSuppliers(bool $activeOnly = false)
    {
        return $this->mainRepository->getAllSuppliers($activeOnly);
    }

    public function getAllSuppliersPaginated(int $perPage = 15, string $search = null)
    {
        return $this->mainRepository->getAllSuppliersPaginated($perPage, false, $search);
    }

    public function getSupplier(int $id)
    {
        return $this->mainRepository->getSupplier($id);
    }

    public function createSupplier(array $data)
    {
        return $this->mainRepository->createSupplier($data);
    }

    public function updateSupplier(int $id, array $data)
    {
        return $this->mainRepository->updateSupplier($id, $data);
    }

    public function deleteSupplier(int $id): bool
    {
        return $this->mainRepository->deleteSupplier($id);
    }
}
