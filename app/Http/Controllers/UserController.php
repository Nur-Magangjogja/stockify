<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    private $userService;

    // public function __construct(UserService $userService)
    // {
    //     $this->userService = $userService;
    // }
    /**
     * findUserId
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findUserId($id)
    {
        $result = $this->userRepository->getUserid($id);
        return response()->json([
            "success" => true,
            "code" => 200,
            "data" => $result
        ]);
    }
    
    // public function create(UserRequest)
    // {

    // }
}