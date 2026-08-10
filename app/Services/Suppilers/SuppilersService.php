<?php

namespace App\Services\Suppilers;

use LaravelEasyRepository\BaseService;

interface SuppilersService extends BaseService{

    public function getAllSuppliers(bool $activeOnly = false);
    public function getAllSuppliersPaginated(int $perPage = 15);
    public function getSupplier(int $id);
    public function createSupplier(array $data);
    public function updateSupplier(int $id, array $data);
    public function deleteSupplier(int $id): bool;

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid);
}
