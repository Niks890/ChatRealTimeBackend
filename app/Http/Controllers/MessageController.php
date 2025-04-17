<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Group;
use Illuminate\Http\Request;

use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected $userRepo;

    public function __construct(MessageRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllMessageOfTwoUser(Request $request)
    {
        $groupId = $request->input('group_id');
        $userId1 = $request->input('user_id');
        $userId2 = $request->input('other_user_id');
        $messages = $this->userRepo->all($groupId, $userId1, $userId2);
        return $this->apiStatus(MessageResource::collection($messages), 200, $messages->count());
    }
}
