<?php

// use Examyou\RestAPI\Facades\ApiRoute;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('team-chat.{teamChatId}', function ($user, $id) {
    return $user->id == $id;
});