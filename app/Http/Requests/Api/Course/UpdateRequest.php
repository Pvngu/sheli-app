<?php

namespace App\Http\Requests\Api\Course;

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
            'course_name' => 'string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ];
    }
}