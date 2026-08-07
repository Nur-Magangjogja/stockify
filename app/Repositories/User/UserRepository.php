<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository{
    
    /**
     * getUserid
     *
     * @param $id
     * @return mixed
     */
    public function getUserid($id);
}
