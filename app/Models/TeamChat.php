<?php

namespace App\Models;

use App\Models\BaseModel;


class TeamChat extends BaseModel
{
    protected $table = 'team_chats';

    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'team_chat_user', 'team_chat_id', 'user_id');
    }

    public function chatUser() {
        return $this->users()->where('user_id', '!=', user()->id)->select('users.id', 'users.name', 'users.role_id', 'users.profile_image');
    }

    public function messages() {
        return $this->hasMany(TeamMessage::class);
    }

    public function unreadMessages() {
        return $this->hasMany(TeamMessage::class)->where('seenAt', null)->where('sender_id', '!=', user()->id);
    }

    public function lastMessage() {
        return $this->hasOne(TeamMessage::class)->orderBy('created_at', 'desc');
    }
}
