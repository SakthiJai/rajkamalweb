<?php

namespace App\Http\Requests\Api\ShippingDetail;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
			'shipping_address'    => 'required|string',
           
		];

     

		return $rules;
    }

    public function messages()
    {
        return [
            'cus_name.required' => 'Shipping address is required',
        ];
    }
}