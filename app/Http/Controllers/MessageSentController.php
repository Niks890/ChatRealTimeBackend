<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageSentController extends Controller
{


    public function sendMessage(Request $request)
    {
        $messageText = $request->input('message');
        $groupId = $request->input('group_id');
        $senderId = $request->input('user_id');

        // Lưu tin nhắn vào DB
        $newMessage = Message::create([
            'group_id' => $groupId,
            'sender_id' => $senderId,
            'message' => $messageText,
            'type' => 'text',
            'file_path' => null,
            'created_at' => now(),
        ]);

        // Lấy tên người gửi (tuỳ bạn lưu như thế nào, ví dụ có quan hệ messages.sender)
        $senderName = User::find($senderId)?->name ?? 'Không rõ';

        $formatted = [
            'content' => $newMessage->message,
            'type' => 'sent', // frontend sẽ dùng cái này
            'senderName' => $senderName,
            'createdAt' => $newMessage->created_at->toISOString(),

        ];

        broadcast(new MessageSent([
            'message' => $newMessage->message,
            'sender_id' => $senderId,
            'senderName' => $senderName,
            'group_id' => $groupId,
            'created_at' => $newMessage->created_at->toISOString(),
            'file_path' => $newMessage->file_path,
            'type' => 'sent' // hoặc 'received' tuỳ người nhận
        ]))->toOthers();


        // Trả về đầy đủ tin nhắn cho FE hiển thị
        return response()->json([
            'success' => true,
            'message' => $formatted
        ]);
    }
}
