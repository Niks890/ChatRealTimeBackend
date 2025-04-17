<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Support\Facades\Log;

class GroupRepository implements GroupRepositoryInterface
{
    public function getOrCreateGroupId($userId1, $userId2)
    {
        $group = Group::whereHas('members', function ($q) use ($userId1, $userId2) {
            $q->whereIn('user_id', [$userId1, $userId2]);
        })
            ->withCount(['members as matched_users_count' => function ($q) use ($userId1, $userId2) {
                $q->whereIn('user_id', [$userId1, $userId2]);
            }])
            ->withCount('members')
            ->having('matched_users_count', 2)
            ->having('members_count', 2)
            ->first();

        // if ($group) {
        //     Log::debug("Đã tìm thấy group: {$group->id}");
        // }

        if (!$group) {
            // Log::info("Không có group chung, tạo mới");

            // 'name',
            // 'avatar',
            // 'is_private',
            // 'created_by',
            $group = Group::create([
                'name' => 'Group' . rand(1, 100),
                'avatar' => 'avatar.png',
                'is_private' => true,
                'created_by' => $userId1,
            ]);
            $group->members()->createMany([
                ['group_id' =>  $group->id, 'user_id' => $userId1, 'role' => 'member', 'joined_at' => now()],
                ['group_id' =>  $group->id, 'user_id' => $userId2, 'role' => 'member', 'joined_at' => now()],
            ]);

            // Log::info("Group mới tạo: {$group->id}");
        }

        return $group->id;
    }
}
