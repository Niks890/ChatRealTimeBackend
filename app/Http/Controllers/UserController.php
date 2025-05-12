<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsersExceptCurrentUser()
    {
        $currentUserId = auth('api')->id();
        $users = $this->userRepo->all($currentUserId);
        return $this->apiStatus($users, 200, $users->count());
    }

    public function getUserByKeyWord(Request $request)
    {
        $keyword = $request->input('keyword');
        $users = $this->userRepo->searchUsers($keyword);
        return $this->apiStatus($users, 200, $users->count());
    }
}
