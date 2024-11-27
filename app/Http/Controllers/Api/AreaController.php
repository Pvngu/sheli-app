<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Area\IndexRequest;
use App\Http\Requests\Api\Area\StoreRequest;
use App\Http\Requests\Api\Area\DeleteRequest;
use App\Http\Requests\Api\Area\UpdateRequest;

use App\Models\Area;

class AreaController extends ApiBaseController
{
    protected $model = Area::class;

    protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;
}
