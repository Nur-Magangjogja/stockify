<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService{
    /**
     * getUserid
     *
     * @param mixed $id
     * @return mixed
     */
    public function getUserid($id);
}
