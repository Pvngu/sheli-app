<?php

namespace App\Http\Controllers\Api;

use App\Models\AuditImage;

use App\Http\Controllers\ApiBaseController;

class AuditImageController extends ApiBaseController
{
    protected $model = AuditImage::class;

    public function upload() {
        $request = request();
        $images= [];

        $auditId = $this->getIdFromHash($request->audit_id);
        $images = $request->images;

        AuditImage::where('audit_id', $auditId)->delete();

        if(count($images) > 0) {
            foreach($images as $image) {
                $auditImage = new AuditImage();
                $auditImage->audit_id = $auditId;
                $auditImage->file = $image;
                $auditImage->save();
            }
        }
    }
}