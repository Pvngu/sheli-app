<?php

namespace App\Models;

use App\Models\BaseModel;

class Transcript extends BaseModel
{

    protected $table = 'transcripts';
    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();
    }

    public function call()
    {
        return $this->belongsTo(Call::class);
    }
}
