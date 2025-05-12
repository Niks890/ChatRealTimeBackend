<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

use App\Repositories\Contracts\MessageRepositoryInterface;

class MessageController extends Controller
{
    protected $messageRepo;

    public function __construct(MessageRepositoryInterface $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    public function getAllMessageOfTwoUser(Request $request)
    {
        $groupId = $request->input('group_id');
        $userId1 = $request->input('user_id');
        $userId2 = $request->input('other_user_id');
        $messages = $this->messageRepo->all($groupId, $userId1, $userId2);
        return $this->apiStatus(MessageResource::collection($messages), 200, $messages->count());
    }

       public function getAllMessageGroup(Request $request)
    {
            // dd('Đã vào controller', $request->all());
        $groupId = $request->input('group_id');
        $messages = $this->messageRepo->getAllMessageGroupOfUser($groupId);
        // if(!$messages){
        //     return $this->apiStatus([], 200, 0);
        // }
        return $this->apiStatus(MessageResource::collection($messages), 200, $messages->count());
    }
}
