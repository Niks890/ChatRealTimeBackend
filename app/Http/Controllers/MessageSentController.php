<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;


class MessageSentController extends Controller
{




    public function sendMessage(Request $request)
    {
        $groupId = $request->input('group_id');
        $senderId = $request->input('user_id');
        $messageText = $request->input('message');
        $filePath = $request->input('file'); // từ FE gửi lên có thể là null hoặc string

        // Nếu là mảng thì lấy phần tử đầu tiên
        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
        }

        // Nếu không có message text và cũng không có file thì lỗi
        if (empty($messageText) && !$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'Phải có nội dung tin nhắn hoặc hình ảnh.'
            ], 422);
        }

        // Xác định loại message
        if ($messageText && $filePath) {
            $type = 'text_file';
        } elseif ($filePath) {
            $type = 'file';
        } else {
            $type = 'text';
        }

        $newMessage = Message::create([
            'group_id' => $groupId,
            'sender_id' => $senderId,
            'message' => $messageText ?: null, // đảm bảo là null nếu không có
            'type' => $type,
            'file_path' => $filePath ?: null,
            'created_at' => now(),
        ]);

        $senderName = User::find($senderId)?->name ?? 'Không rõ';

        $formatted = [
            'content' => $messageText,
            'filePath' => $filePath,
            'type' => 'sent',
            'senderName' => $senderName,
            'messageType' => $type,
            'createdAt' => $newMessage->created_at->toISOString(),
        ];

        broadcast(new MessageSent([
            'message' => $messageText,
            'sender_id' => $senderId,
            'senderName' => $senderName,
            'group_id' => $groupId,
            'created_at' => $newMessage->created_at->toISOString(),
            'file_path' => $filePath,
            'type' => 'sent',
            'messageType' => $type,
        ]))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $formatted
        ]);
    }
}
