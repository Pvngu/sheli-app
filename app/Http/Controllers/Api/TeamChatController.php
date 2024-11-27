<?php

namespace App\Http\Controllers\Api;

use App\Models\TeamChat;
use App\Models\TeamMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiBaseController;

class TeamChatController extends ApiBaseController
{
    protected $model = TeamChat::class;

    public function getChats() {
        $request = request();
        $user = user();

        $query = $user->chats()
            ->with('chatUser.role', 'lastMessage')
            ->orderBy(
                TeamMessage::select('created_at')
                    ->whereColumn('team_chat_id', 'team_chats.id')
                    ->latest()
                    ->take(1),
                    'desc'
            )->withCount('unreadMessages');

        if($request->status !== null && $request->status === 'unread') {
            $query = $query->having('unread_messages_count', '>', 0);
        }

        if($request->nameFilter !== null) {
            $query = $query->whereHas('chatUser', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->nameFilter . '%');
            });
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    public function userHaveChat($userId) {
        $user = user();
        $userId = $this->getIdFromHash($userId);

        //get chat ids of user and current user usign join
        $chatId = DB::table('team_chat_user as t1')
        ->select('t1.team_chat_id')
        ->join('team_chat_user as t2', 't1.team_chat_id', '=', 't2.team_chat_id')
        ->where('t1.user_id', $user->id)
        ->where('t2.user_id', $userId)
        ->pluck('t1.team_chat_id')
        ->first();

        $chat = TeamChat::where('id', $chatId)
        ->with('chatUser.role', 'lastMessage')
        ->first();

        return response()->json([
            'data' => $chat
        ]);
    }
}
