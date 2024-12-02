<?php

namespace App\Http\Requests\Api\Audit;

use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'auditor_id' => 'required',
            'area_id' => 'required',
            'status' => 'required',
        ];
    }
}