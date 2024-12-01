<?php

namespace App\Models;

use App\Classes\Common;
use App\Models\BaseModel;

class Document extends BaseModel
{
    protected $table = 'documents';
    
    protected $default = ["xid", "title"];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'file_url'];

    protected $filterable = ['type'];

    protected static function boot()
    {
        parent::boot();
    }

    public function getFileUrlAttribute()
    {
        $docFilesPath = Common::getFolderPath('docFilesPath');

        return $this->file == null ? null : Common::getFileUrl($docFilesPath, $this->file);
    }
}
