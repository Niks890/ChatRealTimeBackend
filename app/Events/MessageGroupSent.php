<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageGroupSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $messageGroup;

    // Tạo một constructor để nhận message
    public function __construct($messageGroup)
    {
        $this->messageGroup = $messageGroup;
    }

    // Định nghĩa kênh phát sóng

    public function broadcastOn()
    {
        return new Channel('chat-group');
    }
   public function broadcastAs()
    {
        return 'message-group.sent';
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->messageGroup['message'],
            'sender_id' => $this->messageGroup['sender_id'],
            'senderName' => $this->messageGroup['senderName'],
            'group_id' => $this->messageGroup['group_id'],
            'created_at' => $this->messageGroup['created_at'],
            'file_path' => $this->messageGroup['file_path'],
            'type' => $this->messageGroup['type'],
            'messageType' => $this->messageGroup['messageType'],
        ];
    }
}
