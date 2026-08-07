<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
        $result = $this->userService->getUserid($id);
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