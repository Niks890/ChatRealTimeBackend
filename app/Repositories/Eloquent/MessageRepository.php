<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{
    public function all($groupId, $userId1, $userId2)
    {
        return Message::with('sender')
            ->where('group_id', $groupId)
            ->whereIn('sender_id', [$userId1, $userId2])
            ->whereHas('group', function ($q) use ($userId1, $userId2) {
                $q->whereHas('members', fn($q1) => $q1->where('user_id', $userId1))
                    ->whereHas('members', fn($q2) => $q2->where('user_id', $userId2));
            })
            ->orderBy('created_at')
            ->get();
    }


    public function getAllMessageGroupOfUser($groupId)
    {
        return Message::with('sender')
            ->where('group_id', $groupId)
            ->whereHas('group.members')
            ->orderBy('created_at')
            ->distinct()
            ->get();
    }
}
