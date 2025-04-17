<?php

namespace App\Repositories\Contracts;

interface GroupRepositoryInterface
{
    public function getOrCreateGroupId($userId1,  $userId2);
}
