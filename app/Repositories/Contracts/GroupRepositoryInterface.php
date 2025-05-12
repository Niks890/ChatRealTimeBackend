<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface GroupRepositoryInterface
{
    public function getOrCreateGroupId($userId1,  $userId2);

    public function createGroup(array $data);

    public function getGroupOfUser($currentUserId = null);
}
