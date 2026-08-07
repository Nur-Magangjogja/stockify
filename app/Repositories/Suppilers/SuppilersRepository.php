<?php

namespace App\Repositories\Suppilers;

use LaravelEasyRepository\Repository;

interface SuppilersRepository extends Repository{

    /**
     * getSuppliers
     *
     * @param $suppliersid
     * @return mixed
     */
    public function getSuppliers($suppliersid);
}
