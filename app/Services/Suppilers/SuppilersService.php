<?php

namespace App\Services\Suppilers;

use LaravelEasyRepository\BaseService;

interface SuppilersService extends BaseService{

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid);
}
