<?php

namespace App\Http\Requests\Api\Document;

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
            'name' => 'required|string',
            'type' => 'required|string',
            'file' => 'required|string',
        ];
    }
}