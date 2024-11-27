<?php

namespace App\Models;

use App\Models\BaseModel;


class TeamMessage extends BaseModel
{
    protected $table = 'team_messages';

    protected $appends = ['xid', 'x_sender_id'];

    protected $hidden = ['id', 'team_chat_id', 'sender_id'];

    protected $hashableGetterFunctions = [
        'getXSenderIdAttribute' => 'sender_id',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function teamChat()
    {
        return $this->belongsTo(TeamChat::class);
    }
}
