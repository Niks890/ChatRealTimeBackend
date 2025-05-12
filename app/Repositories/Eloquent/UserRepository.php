<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{


    public function all($currentUserId = null)
{
    // Log::info('Current user ID in all():', ['id' => $currentUserId]);
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

    public function searchUsers($keyword)
    {
        return User::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->get();
    }

}
