<?php

namespace App\Http\Requests\Api\Audit;
use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'audit_name' => 'required|string',
            'audit_date' => 'required|date',
            'auditor_id' => 'required|integer',
            'status' => 'required|string|in:pending,completed',
        ];
    }
}