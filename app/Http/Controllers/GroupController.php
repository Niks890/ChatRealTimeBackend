<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    protected $groupRepo;

    public function __construct(GroupRepositoryInterface $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }
    public function getGroupId(Request $request)
    {
        $userId1 = $request->input('user_id');
        $userId2 = $request->input('other_user_id');
        $groupId = $this->groupRepo->getOrCreateGroupId($userId1, $userId2);
        return $this->apiStatus($groupId, 200);
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'admin' => 'required',
            'members' => 'required|array|min:3',
            // 'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload avatar nếu có
        // $avatarPath = null;
        // if ($request->hasFile('avatar')) {
        //     $avatarPath = $request->file('avatar')->store('group_avatars', 'public');
        // }

        $data = [
            'name' => $request->input('name'),
            'admin' => $request->input('admin'),
            'members' => $request->input('members'),
            // 'avatar' => $avatarPath,
        ];

        $groupId = $this->groupRepo->createGroup($data);

        return $this->apiStatus($groupId, 200);
    }

    public function getAllGroupOfUsers()
    {
        $currentUserId = auth('api')->id();
        // Log::info('Current User ID:', ['user_id' => $currentUserId]);

        $groups = $this->groupRepo->getGroupOfUser($currentUserId);
        return $this->apiStatus($groups, 200, $groups->count());
    }



}
