<?php

namespace App\Http\Requests\Api\SalesNumber;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name'    => 'required',
            'mobile_number' =>'requried',
            'gender' => 'requried',
            'age' => 'requried',
            'billing_discount' => 'requried',
            'customer_type' => 'requried',
            'customer_status' => 'requried',
        ];

        return $rules;

    }

}
