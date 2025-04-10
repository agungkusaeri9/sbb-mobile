<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function allByUserId(Request $request)
    {
        $userId = Auth::id();
        $withUserId = $request->input('with_user_id');

        $chats = Chat::with(['sender', 'receiver'])->where(function ($query) use ($userId, $withUserId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $withUserId);
        })->orWhere(function ($query) use ($userId, $withUserId) {
            $query->where('sender_id', $withUserId)
                ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        return ResponseFormatter::success($chats, 'Chats Fetched Successfully');
    }

    public function index()
    {
        $userId = Auth::id();
        $chats = Chat::with(['sender', 'receiver'])->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get();

        $grouped = $chats->groupBy(function ($chat) use ($userId) {
            return $chat->sender_id == $userId ? $chat->receiver_id : $chat->sender_id;
        });
        $result = $grouped->map(function ($chatGroup) {
            return $chatGroup->first();
        })->values();

        return ResponseFormatter::success($result, 'List of Conversations Fetched');
    }


    // Menyimpan pesan baru
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return ResponseFormatter::success($chat, 'Message Sent Successfully');
    }

    // Menampilkan 1 chat (optional)
    public function show($id)
    {
        $chat = Chat::find($id);

        if (!$chat) {
            return ResponseFormatter::error(null, 'Chat Not Found', 404);
        }

        return ResponseFormatter::success($chat, 'Chat Fetched Successfully');
    }

    // Menandai pesan sebagai dibaca
    public function markAsRead($id)
    {
        $chat = Chat::find($id);

        if (!$chat) {
            return ResponseFormatter::error(null, 'Chat Not Found', 404);
        }

        $chat->update(['is_read' => true]);

        return ResponseFormatter::success($chat, 'Message marked as read');
    }
}
