<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    // Tạo một constructor để nhận message
    public function __construct($message)
    {
        $this->message = $message;
    }

    // Định nghĩa kênh phát sóng
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    // Định nghĩa sự kiện để broadcast
    public function broadcastAs()
    {
        return 'message.sent';
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->message['message'],
            'sender_id' => $this->message['sender_id'],
            'senderName' => $this->message['senderName'],
            'group_id' => $this->message['group_id'],
            'created_at' => $this->message['created_at'],
            'file_path' => $this->message['file_path'],
            'type' => $this->message['type'],
        ];
    }
}
