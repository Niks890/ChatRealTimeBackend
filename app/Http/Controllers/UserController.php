<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsersExceptCurrentUser()
    {
        $currentUserId = auth()->id();
        $users = $this->userRepo->all($currentUserId);
        return $this->apiStatus($users, 200, $users->count());
    }
}
