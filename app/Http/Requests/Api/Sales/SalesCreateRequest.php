<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SalesCreateRequest extends FormRequest
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
            'party_name' => 'required',
            'bill_number' => 'required',
            'order_status' => 'required',
            'party_customer_mobile' => 'required',
            'party_customer_name' => 'required',
            'address' => 'required',
        ];

        return $rules;
    }
}
