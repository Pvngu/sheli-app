<?php

namespace App\Models;

use App\Models\BaseModel;

class AppNotification extends BaseModel
{

    protected $appends = ['xid'];
    protected $hidden = ['sender_id', 'document_id', 'individual_log_id', 'individual_id'];

    protected static function boot()
    {
        parent::boot();
    }

    // public function enrollment() {
    //     return $this->belongsTo(Enrollment::class, 'enrollment_id');
    // }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user() {
        return $this->belongsToMany(User::class, 'app_notification_users');
    }
}
