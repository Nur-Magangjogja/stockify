<?php

namespace App\Repositories\Suppilers;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Suppilers;

class SuppilersRepositoryImplement extends Eloquent implements SuppilersRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Suppilers $model)
    {
        $this->model = $model;
    }

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid)
    {
        return $this->model->find($suppliersid);
    }

    public function getAllSuppliers(bool $activeOnly = false)
    {
        return $this->model->orderBy('name')->get();
    }

    public function getAllSuppliersPaginated(int $perPage = 15, bool $activeOnly = false, string $search = null)
    {
        $query = $this->model->orderBy('name');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        return $query->paginate($perPage)->appends(['search' => $search]);
    }

    public function getSupplier(int $id): Suppilers
    {
        return $this->model->findOrFail($id);
    }

    public function createSupplier(array $data): Suppilers
    {
        return $this->model->create($data);
    }

    public function updateSupplier(int $id, array $data): Suppilers
    {
        $supplier = $this->model->findOrFail($id);
        $supplier->update($data);

        return $supplier->refresh();
    }

    public function deleteSupplier(int $id): bool
    {
        return (bool) $this->model->findOrFail($id)->delete();
    }
}
