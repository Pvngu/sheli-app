<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Accident\IndexRequest;
use App\Http\Requests\Api\Accident\UpdateRequest;
use App\Http\Requests\Api\Accident\DeleteRequest;
use App\Http\Requests\Api\Accident\StoreRequest;

use App\Models\Accident;

class AccidentController extends ApiBaseController
{
	protected $model = Accident::class;

	protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;

    public function modifyIndex($query) {
        $request = request();

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('accidents.date_of_accident >= ?', [$startDate])
                ->whereRaw('accidents.date_of_accident <= ?', [$endDate]);
        }
        return $query;
    }
}
