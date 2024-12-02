<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\BaseModel;

class Area extends BaseModel
{

    use HasFactory;

    protected $table = 'areas';
    
    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];

    protected $hidden = [ 'id'];

    protected static function boot()
    {
        parent::boot();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'area_id', 'id');
    }

    public function accidents()
    {
        return $this->hasMany(Accident::class, 'area_id', 'id');
    }
}
