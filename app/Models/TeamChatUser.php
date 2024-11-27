<?php

namespace App\Models;

use App\Models\BaseModel;


class TeamChatUser extends BaseModel
{
    protected $table = 'team_chat_user';

    protected $appends = ['xid', 'x_team_chat_id', 'x_user_id'];

    protected $hidden = ['id', 'team_chat_id', 'user_id'];

    protected $hashableGetterFunctions = [
        'getXTeamChatIdAttribute' => 'team_chat_id',
        'getXUserIdAttribute' => 'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
