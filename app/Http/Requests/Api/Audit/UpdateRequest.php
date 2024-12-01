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
            'audit_name' => 'sometimes|required|string',
            'audit_date' => 'sometimes|required|date',
            'auditor_id' => 'sometimes|required|integer',
            'status' => 'sometimes|required|string|in:pending,completed',
        ];
    }
}