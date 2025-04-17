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

        if ($group) {
            Log::debug("Đã tìm thấy group: {$group->id}");
        }

        if (!$group) {
            Log::info("Không có group chung, tạo mới");

            $group = Group::create([
                'is_private' => true,
                'created_by' => $userId1,
            ]);

            $group->members()->createMany([
                ['user_id' => $userId1],
                ['user_id' => $userId2],
            ]);

            Log::info("Group mới tạo: {$group->id}");
        }

        return $group->id;
    }
}
