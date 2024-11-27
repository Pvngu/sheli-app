<?php


namespace App\Http\Requests\Api\Accident;

use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'date_of_accident' => 'required|date',
			'injured_person' => 'required|string',
			'reporting_user' => 'required|string',
			'area_id' => 'required|string',
			'description' => 'required|string',
			'status' => 'required|string',
			'days_absent' => 'integer',
		];

		return $rules;
	}
}
