<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends BaseModel
{
    use HasFactory;

    protected $table = 'courses';
    
    protected $default = ['xid'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['xid', 'x_trainer_id'];
    protected $hidden = ['id', 'trainer_id'];
    protected $filterable = ['trainer_id', 'status'];

    protected $hashableGetterFunctions = [
        'getXTrainerIdAttribute' => 'trainer_id',
    ];

    protected $casts = [
        'trainer_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}