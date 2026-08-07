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
}
