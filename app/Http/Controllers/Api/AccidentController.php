<?php

namespace App\Http\Controllers\Api;

use App\Models\Accident;
use App\Exports\AccidentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiBaseController;

use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\Accident\IndexRequest;
use App\Http\Requests\Api\Accident\StoreRequest;
use App\Http\Requests\Api\Accident\DeleteRequest;
use App\Http\Requests\Api\Accident\UpdateRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function export(): BinaryFileResponse
    {
        $request = request();
        $user = user();
        
        $columns = (array) $request->columns;
        $format = $request->format;
        $startDate = "";
        $endDate = "";
        $selectedRowKeys = (array) $request->selectedRowKeys;

        if ($request->has('dates') && $request->dates != "") {
            $dates = $request->dates;
            $startDate = $dates[0];
            $endDate = $dates[1];
        }

        if(!$user->ability('admin', 'reports_view')) {
            throw new ApiException("Not Allowed");
        }

        return Excel::download(new AccidentsExport($columns, $startDate, $endDate, $selectedRowKeys), "accidents.$format");
    }
}
