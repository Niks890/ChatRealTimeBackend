<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageSentController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate dữ liệu tin nhắn (nếu cần)
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');

        // Gửi event MessageSent và broadcast tin nhắn đến những người dùng khác
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'sent']);
    }
}
