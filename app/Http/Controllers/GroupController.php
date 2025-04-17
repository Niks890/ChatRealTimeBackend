<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    protected $userRepo;

    public function __construct(GroupRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function getGroupId(Request $request)
    {
        $userId1 = $request->input('user_id');
        $userId2 = $request->input('other_user_id');
        $groupId = $this->userRepo->getOrCreateGroupId($userId1, $userId2);
        return $this->apiStatus($groupId, 200);
    }
}
