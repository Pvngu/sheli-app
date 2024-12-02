<?php

namespace App\Http\Requests\Api\Enrollment;

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
            'status' => 'required|string|max:255',
        ];
    }
}