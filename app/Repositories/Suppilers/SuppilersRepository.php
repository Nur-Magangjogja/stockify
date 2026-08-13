<?php

namespace App\Repositories\Suppilers;

use LaravelEasyRepository\Repository;
use App\Models\Suppilers;

interface SuppilersRepository extends Repository{

    public function getAllSuppliers(bool $activeOnly = false);
    public function getAllSuppliersPaginated(int $perPage = 15, bool $activeOnly = false, string $search = null);
    public function getSupplier(int $id): Suppilers;
    public function createSupplier(array $data): Suppilers;
    public function updateSupplier(int $id, array $data): Suppilers;
    public function deleteSupplier(int $id): bool;

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid);
}
