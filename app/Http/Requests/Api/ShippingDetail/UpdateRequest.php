<?php

namespace App\Http\Requests\Api\ShippingDetail;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class UpdateRequest extends FormRequest
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
			'shipping_pincode'    => 'required',
            'shipping_address' =>'requried',
            'shipping_city' => 'requried',
            'shipping_contact_number' => 'requried',

		];

		return $rules;
	}
}
