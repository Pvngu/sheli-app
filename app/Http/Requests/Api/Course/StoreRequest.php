<?php

namespace App\Http\Requests\Api\Course;

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
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }
}