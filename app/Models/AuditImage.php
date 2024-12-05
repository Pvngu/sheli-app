<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditImage extends BaseModel
{
    use HasFactory;

    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'x_audit_id', 'url'];

    protected $hidden = ['id', 'audit_id'];

    protected $hashableGetterFunctions = [
        'getXAuditIdAttribute' => 'audit_id',
    ];

    protected $casts = [
        'audit_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function getUrlAttribute()
    {
        $auditFilesPath = Common::getFolderPath('auditFilesPath');

        return $this->file == null ? null : Common::getFileUrl($auditFilesPath, $this->file);
    }
}