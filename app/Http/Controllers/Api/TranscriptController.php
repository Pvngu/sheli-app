<?php

namespace App\Http\Controllers\Api;

use App\Models\Transcript;

use App\Http\Controllers\ApiBaseController;

class TranscriptController extends ApiBaseController
{
    protected $model = Transcript::class;
}
