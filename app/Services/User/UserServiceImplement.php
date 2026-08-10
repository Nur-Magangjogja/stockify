<?php

namespace App\Services\User;

use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;
use App\Models\User;

class UserServiceImplement extends Service implements UserService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * getUserid
     *
     * @param mixed $id
     * @return mixed
     */
    public function getUserid($id)
    {
        return $this->mainRepository->getUserid($id);
    }

    public function getAllUsers(array $filters = [], int $perPage = 15)
    {
      return User::when($filters['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
        ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
        ->latest()->paginate($perPage)->appends($filters);
    }

    public function getUser(int $id) { return User::findOrFail($id); }
    public function createUser(array $data) { return User::create($data); }
    public function updateUser(int $id, array $data) { $user = User::findOrFail($id); $user->update($data); return $user->refresh(); }
    public function deleteUser(int $id): bool { return (bool) User::findOrFail($id)->delete(); }
    public function toggleStatus(int $id): bool { return true; }
}
