<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Document\IndexRequest;
use App\Http\Requests\Api\Document\StoreRequest;
use App\Http\Requests\Api\Document\DeleteRequest;
use App\Http\Requests\Api\Document\UpdateRequest;

use App\Models\Document;

class DocumentController extends ApiBaseController
{
    protected $model = Document::class;

    protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;

    public function deleteFile($fileName) {
        $filePath = public_path('uploads/documents/' . $fileName);

        if(file_exists($filePath)) {
            unlink($filePath);
        }
    }
}