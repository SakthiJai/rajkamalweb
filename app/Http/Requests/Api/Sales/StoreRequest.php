<?php

namespace App\Http\Requests\Api\Sales;

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
    // public function rules()
    // {
    //     $company = company();
    //     $loggedUser = auth('api')->user();

    //     $rules = [
    //         'bill_number' => 'required',
    //         //'user_id' => 'required',
    //         // 'order_status' => 'required',
    //         //'product_items'    => 'required',
    //         //'order_date'    => 'required',
    //     ];

    //     return $rules;
    
    // }
    public function rules(): array
    {
       
        $rules = [
			    'bill_number'    => 'required',
            
		];
		return $rules;
    }

    public function messages()
    {
        return [
            'bill_number.required' => 'Bill Number is required.',
           
        ];
      
    }
}
