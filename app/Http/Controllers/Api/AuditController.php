<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Audit\IndexRequest;
use App\Http\Requests\Api\Audit\StoreRequest;
use App\Http\Requests\Api\Audit\DeleteRequest;
use App\Http\Requests\Api\Audit\UpdateRequest;

use App\Models\Audit;

class AuditController extends ApiBaseController
{
    protected $model = Audit::class;

    protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;
}