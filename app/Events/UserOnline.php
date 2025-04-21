<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnline implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;
    public $last_seen;

    public function __construct($userId, $status = 'online', $last_seen = null)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->last_seen = $last_seen;
    }

    public function broadcastOn()
    {
        return new Channel('user-status');
    }

    public function broadcastAs()
    {
        return 'user.status';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'status' => $this->status,
            'last_seen' => $this->last_seen
        ];
    }
}
