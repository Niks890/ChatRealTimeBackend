<?php

namespace App\Repositories\Contracts;

interface MessageRepositoryInterface
{
    public function all($groupId,  $userId1,  $userId2);
}
