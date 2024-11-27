<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TeamChat;
use App\Events\MessageSent;
use App\Models\TeamMessage;
use App\Models\TeamChatUser;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\TeamMessage\StoreRequest;
use App\Http\Requests\Api\TeamMessage\UpdateRequest;

class TeamMessageController extends ApiBaseController
{
    protected $model = TeamMessage::class;

    public function getMessages($chatId) {
        $request = request();
        $page = $request->page ?? 1;
        // get sent message count to determine the number of messages to load
        $sentMsgCount = $request->sentMsgCount ?? 0;

        $count = TeamMessage::where('team_chat_id', $chatId)->count();
        $takeNumber = 50;

        $maxPage = ceil($count / $takeNumber );

        $currentItems = $page * $takeNumber;
        $leftItems = $count - ($currentItems);
        $limit = $leftItems > 0 ? $takeNumber : $leftItems + $takeNumber;

        $paginatedMessages = TeamMessage::where('team_chat_id', $chatId)
        ->skip($leftItems)
        ->take($limit - $sentMsgCount)
        ->get();

        $groupedMessages = $paginatedMessages->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        // Reverse method was added to get the messages in the right position when new messages are loaded
        if($page != 1) {
            $groupedMessages = $groupedMessages->reverse();
        }
                                
        return response()->json([
            'data' => $groupedMessages,
            'max_page' => $maxPage,
        ], 200);
    }
    
    public function sendMessage() {
        $request = request();
        $user = user();
        $success = true;

        $chatId = $request->team_chat_id;

        if(!$chatId) {
            $teamChat = new TeamChat();
            $teamChat->save();
            $chatId = $teamChat->id;

            $receiverId = $this->getIdFromHash($request->x_user_id);

            $teamChat->users()->attach([$user->id, $receiverId]);
        }

        $message = new TeamMessage();
        $message-> team_chat_id = $chatId;
        $message-> sender_id = $user->id;
        $message->content = $request->body;
        $message->save();

        // broadcast(new MessageSent($message))->toOthers();
        // broadcast(new MessageSent($message, $userId));

        if(!$message) {
            $success = false;
        }

        return ApiResponse::make('Success', [
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function updateReadStatus() {
        $request= request();
        $user = user();

        TeamMessage::where('team_chat_id', $request->team_chat_id)
                    ->where('sender_id', '!=', $user->id)
                    ->update(['seenAt' => Carbon::now()->setTimezone('America/Los_Angeles')]);

        return ApiResponse::make('Success', [
            'success' => true,
        ]);
    }
}
