<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all($currentUserId = null)
    {
        return User::where('id', '!=', $currentUserId)->get();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        return $user ? $user->update($data) : false;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
