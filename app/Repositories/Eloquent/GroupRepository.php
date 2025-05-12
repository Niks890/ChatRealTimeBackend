<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Models\GroupMember;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function createGroup(array $data)
    {
        DB::beginTransaction();

        try {
            // Tạo nhóm mới
            $group = Group::create([
                'name' => $data['name'],
                'created_by' => $data['admin'],
                'is_private' => false,
            ]);

            // Lấy danh sách thành viên và thêm admin nếu chưa có
            $memberIds = $data['members'];

            // Kiểm tra nếu admin chưa có trong danh sách thành viên thì thêm vào
            if (!in_array($data['admin'], $memberIds)) {
                $memberIds[] = $data['admin'];
            }

            // Tạo các đối tượng GroupMember từ danh sách memberIds
            $members = [];
            foreach ($memberIds as $userId) {
                $role = ($userId == $data['admin']) ? 'admin' : 'member'; // Kiểm tra nếu là admin thì gán role admin
                $members[] = new GroupMember([
                    'user_id' => $userId,
                    'role' => $role // Gán role cho từng thành viên
                ]);
            }

            // Lưu tất cả thành viên vào bảng liên kết
            $group->members()->saveMany($members);

            DB::commit();

            return $group->id;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getGroupOfUser($currentUserId = null)
    {
        return Group::whereHas('members', function ($query) use ($currentUserId) {
            $query->where('user_id', $currentUserId);
        })
            ->where('is_private', false)
            ->withCount('members')
            ->get();
    }
}
