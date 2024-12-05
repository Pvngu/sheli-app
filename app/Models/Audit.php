<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends BaseModel
{
    use HasFactory;

    protected $table = 'audits';

    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'x_auditor_id', 'x_area_id'];

    protected $hidden = ['id', 'auditor_id', 'area_id'];

    protected $filterable = ['auditor_id', 'area_id', 'status'];

    protected $hashableGetterFunctions = [
        'getXAuditorIdAttribute' => 'auditor_id',
        'getXAreaIdAttribute' => 'area_id',
    ];

    protected $casts = [
        'auditor_id' => Hash::class . ':hash',
        'area_id' => Hash::class . ':hash'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function auditor()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(AuditImage::class, 'audit_id', 'id');
    }
}