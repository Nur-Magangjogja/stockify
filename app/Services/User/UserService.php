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
    public function getAllUsers(array $filters = [], int $perPage = 15);
    public function getUser(int $id);
    public function createUser(array $data);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id): bool;
    public function toggleStatus(int $id): bool;
}
