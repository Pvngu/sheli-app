<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Enrollment\IndexRequest;
use App\Http\Requests\Api\Enrollment\StoreRequest;
use App\Http\Requests\Api\Enrollment\DeleteRequest;
use App\Http\Requests\Api\Enrollment\UpdateRequest;
use App\Models\Enrollment;

class EnrollmentController extends ApiBaseController
{
    protected $model = Enrollment::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;

    public function modifyIndex($query) {
        $request = request();

        $query = $query->join('courses', 'courses.id', '=', 'enrollments.course_id');

        if($request->has('status') && $request->status != null) {
            $query = $query->where('courses.status', $request->status);
        }

        return $query;
    }
}