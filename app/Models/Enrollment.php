<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends BaseModel
{
    use HasFactory;

    protected $table = 'enrollments';
    
    protected $default = ['xid'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['xid', 'x_user_id', 'x_course_id'];
    protected $hidden = ['id', 'user_id', 'course_id'];
    protected $filterable = ['status'];

    protected $hashableGetterFunctions = [
        'getXUserIdAttribute' => 'user_id',
        'getXCourseIdAttribute' => 'course_id',
    ];

    protected $casts = [
        'user_id' => Hash::class . ':hash',
        'course_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}