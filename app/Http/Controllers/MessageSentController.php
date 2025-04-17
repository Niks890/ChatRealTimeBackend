<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageSentController extends Controller
{
    // public function sendMessage(Request $request)
    // {
    //     // Validate dữ liệu tin nhắn (nếu cần)
    //     $request->validate([
    //         'message' => 'required|string',
    //     ]);

    //     $message = $request->input('message');

    //     // Gửi event MessageSent và broadcast tin nhắn đến những người dùng khác
    //     broadcast(new MessageSent($message))->toOthers();

    //     return response()->json(['status' => 'sent']);
    // }

    public function sendMessage(Request $request)
    {
        // $request->validate([
        //     'message' => 'required|string',
        //     'group_id' => 'required|integer',
        // ]);


        $message = $request->input('message');
        $groupId = $request->input('group_id');
        $senderId = $request->input('user_id');


        // Lưu vào DB
        Message::create([
            'group_id' => $groupId,
            'sender_id' => $senderId,
            'message' => $message,
            'type' => 'text',
            'file_path' => 'anh.png',
            'created_at' => now(),
        ]);


        // Broadcast tin nhắn
        // broadcast(new MessageSent([
        //     'message' => $message,
        //     // 'sender_id' => $message->sender_id,
        //     // 'created_at' => $message->created_at->toDateTimeString(),
        // ]))->toOthers();
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'sent']);
    }
}
